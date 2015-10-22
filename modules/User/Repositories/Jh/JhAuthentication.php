<?php namespace modules\User\Repositories\Jh;

use Modules\Core\Contracts\Authentication;
use Modules\User\Entities\User;
use Modules\User\Entities\Persistences;

class JhAuthentication implements Authentication
{
    protected $user;
    
    protected $persistences;
    
    protected $users;


    public function __construct(
            User $users,
            Persistences $persistences
    ) {
        $this->users = $users;
        $this->persistences = $persistences;
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
        //check exist user
        if ($this->user !== null) {
            return $this->user;
        }
        //get code of persistences        
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
