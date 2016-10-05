<?php namespace Modules\User\Repositories\Persistences;

use Modules\User\Repositories\Persistences\PersistableInterface;
use Modules\User\Repositories\Cookies\CookieRepositoryInterface;
use Modules\User\Repositories\Sessions\SessionRepositoryInterface;

class PersistenceRepository implements PersistenceRepositoryInterface
{
    protected $session;
    protected $cookie;
    protected $single = true;

    protected $model;

    public function __construct(
            SessionRepositoryInterface $session,
            CookieRepositoryInterface $cookie,
            $model = null
    ) {
        $this->session  = $session;
        $this->cookie   = $cookie;
        $this->model    = $model;
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
     * {@inheritDoc}
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
        
        $this->model->code = $code;

        return $this->model->save();
    }

    /**
     * {@inheritDoc}
     */
    public function persistAndRemember(PersistableInterface $persistable)
    {
        return $this->persist($persistable, true);
    }
    
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function remove($code)
    {
        return $this->model
            ->newQuery()
            ->where('code', $code)
            ->delete();
    }
    
    /**
     * {@inheritDoc}
     */
    public function findByPersistenceCode($code)
    {
        $persistence = $this->model
            ->newQuery()
            ->where('code', $code)
            ->first();

        return $persistence ? $persistence : false;
    }
    
    /**
     * {@inheritDoc}
     */
    public function findUserByPersistenceCode($code)
    {
        $persistence = $this->findByPersistenceCode($code);

        return $persistence ? $persistence->user : false;
    }

    /**
     * {@inheritDoc}
     */
    public function flush(PersistableInterface $persistable, $forget = true)
    {
        
        if ($forget) {
            $this->forget($persistable);
        }        
        
        foreach ($persistable->{$persistable->getPersistableRelationship()}()->get() as $persistence) {
            
            if ($persistence->code !== $this->check()) {                
                $persistence->delete();
            }
        }
    }
}
