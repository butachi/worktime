<?php namespace Modules\User\Repositories\Jhawaii;

use Modules\Core\Contracts\Authentication;
use Modules\User\Facades\Jhawaii;
use Modules\User\Repositories\User\UserRepository;
use Modules\User\Entities\Persistences;

class JhawaiiAuthentication implements Authentication
{
    protected $user;
    
    protected $persistences;
    
    protected $users;


    public function __construct(
            UserRepository $users,
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
        return Jhawaii::get();
        //return $this->users->get();
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
