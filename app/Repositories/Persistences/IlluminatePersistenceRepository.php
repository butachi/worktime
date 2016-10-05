<?php

namespace App\Repositories\Persistences;

use App\Repositories\Cookies\CookieRepositoryInterface;
use App\Repositories\Sessions\SessionRepositoryInterface;

class IlluminatePersistenceRepository implements PersistenceRepositoryInterface
{
    protected $session;
    protected $cookie;
    protected $single = true;
    protected $ipAddress = null;
    protected $userAgent = null;

    protected $model;

    public function __construct(
        SessionRepositoryInterface $session,
        CookieRepositoryInterface $cookie,
        $model = null,
        $ipAddress = null,
        $userAgent = null
    ) {
        $this->session = $session;
        $this->cookie = $cookie;
        $this->model = $model;

        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    public function check()
    {
        if ($code = $this->session->get()) {
            return $code;
        }

        if ($code = $this->cookie->get()) {
            return $code;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function persist(PersistableInterface $persistable, $remember = false)
    {
        if ($this->single) {
            $this->flush($persistable);
        }

        $code = $persistable->generatePersistenceCode();

        $this->session->put($code);

        if ($remember === true) {
            $this->cookie->put($code);
        }
        $this->model->{$persistable->getPersistableKey()} = $persistable->getPersistableId();
        $this->model->cookie_value = $code;
        $this->model->ip_addr = $this->ipAddress;
        $this->model->user_agent = $this->userAgent;

        return $this->model->save();
    }

    /**
     * {@inheritdoc}
     */
    public function persistAndRemember(PersistableInterface $persistable)
    {
        return $this->persist($persistable, true);
    }

    /**
     * {@inheritdoc}
     */
    public function forget()
    {
        $code = $this->check();
        if ($code === null) {
            return;
        }

        $this->session->forget();
        $this->cookie->forget();

        return $this->remove($code);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($code)
    {
        return $this->model
            ->newQuery()
            ->where('cookie_value', $code)
            ->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function findByPersistenceCode($code)
    {
        $persistence = $this->model
            ->newQuery()
            ->where('cookie_value', $code)
            ->first();

        return $persistence ? $persistence : false;
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByPersistenceCode($code)
    {
        $persistence = $this->findByPersistenceCode($code);

        return $persistence ? $persistence->user : false;
    }

    /**
     * {@inheritdoc}
     */
    public function flush(PersistableInterface $persistable, $forget = true)
    {
        if ($forget) {
            $this->forget($persistable);
        }
        foreach ($persistable->{$persistable->getPersistableRelationship()}()->get() as $persistence) {
            if ($persistence->cookie_value !== $this->check()) {
                $persistence->delete();
            }
        }
    }
}
