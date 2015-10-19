<?php namespace Modules\User\Repositories\Jh;

use Modules\Core\Contracts\Authentication;
use Modules\User\Repositories;

class JhAuthentication implements Authentication
{
    protected $user;
    
    protected $persistences;
    
    public function __construct() {        
    }

    public function login()
    {
    }
    
    public function register()
    {
    }
    
    public function active()
    {
    }
    
    public function assignRole()
    {
    }
    
    public function logout()
    {
    }
    
    public function check()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        if (! $code = $this->persistences->check()) {
            return false;
        }

        if (! $user = $this->persistences->findUserByPersistenceCode($code)) {
            return false;
        }

        if (! $this->cycleCheckpoints('check', $user)) {
            return false;
        }

        return $this->user = $user;
    }
    
    public function id()
    {
        
    }
}
