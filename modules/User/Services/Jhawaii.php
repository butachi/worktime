<?php namespace Modules\User\Services;

use Modules\User\Repositories\Users\UserRepository;
use Modules\User\Repositories\Persistences\PersistenceRepositoryInterface;

class Jhawaii 
{
    protected $user;
 
    protected $users;
    
    protected $persistences;


    public function __construct(
        UserRepository $users,
        PersistenceRepositoryInterface $persistence
    ) {
        
        $this->users = $users;
        $this->persistences = $persistence;
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
    
    public function getUserRepository()
    {
        return $this->users;
    }
}
