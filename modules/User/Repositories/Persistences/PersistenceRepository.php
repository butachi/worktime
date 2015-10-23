<?php namespace Modules\User\Repositories\Persistences;

use Modules\User\Repositories\Cookies\CookieRepositoryInterface;
use Modules\User\Repositories\Sessions\SessionRepositoryInterface;

class PersistenceRepository implements PersistenceRepositoryInterface
{
    protected $session;
    protected $cookie;
    
    public function __construct(
            SessionRepositoryInterface $session,
            CookieRepositoryInterface $cookie
    ) {
        $this->session = $session;
        $this->cookie = $cookie;
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
}

