<?php namespace modules\User\Services;

use Modules\User\Repositories\Users\UserRepositoryInterface;
use Modules\User\Repositories\Users\UserInterface;
use Modules\User\Repositories\Persistences\PersistenceRepositoryInterface;
use Illuminate\Events\Dispatcher;

class Jhawaii
{
    protected $user;
 
    protected $users;
    
    protected $persistences;
    
    protected $dispatcher;
    
    /**
     * Array that holds all the enabled checkpoints.
     *
     * @var array
     */
    protected $checkpoints = [];
    
    /**
     * Flag for the checkpoint status.
     *
     * @var bool
     */
    protected $checkpointsStatus = true;

    public function __construct(
        UserRepositoryInterface $users,
        PersistenceRepositoryInterface $persistence,
        Dispatcher $dispatcher = null
    ) {
        $this->users = $users;
        $this->persistences = $persistence;
        $this->dispatcher   = $dispatcher;
    }
    public function check()
    {
        if ($this->user !== null) {
            return $this->user;
        }
        
        if (! $code = $this->persistences->check()) {
            return false;
        }
        $user = $this->persistences->findUserByPersistenceCode($code);
        
        if (! $user = $this->persistences->findUserByPersistenceCode($code)) {
            return false;
        }
        
        if (! $this->cycleCheckpoints('check', $user)) {
            return false;
        }
        
        return $this->user = $user;
    }
    
    /**
     * Authenticates a user, with "remember" flag.
     * 
     * @param \Modules\User\Repositories\Users\UserInterface|array $credentials
     * @param bool $remember
     * @param boll $login
     * @return \Modules\User\Repositories\Users\UserInterface|bool
     */
    public function authenticate($credentials, $remember = false, $login = true)
    {
        $response = $this->dispatcher->fire('jhawaii.authenticating', $credentials, true);
        
        if ($response === false) {
            return false;
        }
        
        if ($credentials instanceof UserInterface) {
            $user = $credentials;
        } else {
            $user = $this->users->findByCredentials($credentials);
            
            $valid = $user !== null ? $this->users->validateCredentials($user, $credentials) : false;
            
            if ($user === null || $valid === false) {
                $this->cycleCheckpoints('fail', $user, false);

                return false;
            }
        }
        
        if (! $this->cycleCheckpoints('login', $user)) {
            return false;
        }
        
        if ($login === true) {
            $method = $remember === true ? 'loginAndRemember' : 'login';

            if (! $user = $this->{$method}($user)) {
                return false;
            }
        }
        $this->dispatcher->fire('jhawaii.authenticated', $user);
        return $this->user = $user;
    }
    
    public function getUserRepository()
    {
        return $this->users;
    }
    
    /**
     * Persists a login for the given user.
     *
     * @param  \Modules\User\Repositories\Users\UserInterface  $user
     * @param  bool  $remember
     * @return \Modules\User\Repositories\Users\UserInterface|bool
     */
    public function login(UserInterface $user, $remember = false)
    {
        $method = $remember === true ? 'persistAndRemember' : 'persist';

        $this->persistences->{$method}($user);

        $response = $this->users->recordLogin($user);

        if ($response === false) {
            return false;
        }
        return $this->user = $user;
    }

    /**
     * Persists a login for the given user, with the "remember" flag.
     *
     * @param  \Modules\User\Repositories\Users\UserInterface  $user
     * @return \Modules\User\Repositories\Users\UserInterface|bool
     */
    public function loginAndRemember(UserInterface $user)
    {
        return $this->login($user, true);
    }
    
    /**
     * Cycles through all the registered checkpoints for a user. Checkpoints
     * may throw their own exceptions, however, if just one returns false,
     * the cycle fails.
     *
     * @param  string  $method
     * @param  \Modules\User\Repositories\Users\UserInterface  $user
     * @param  bool  $halt
     * @return bool
     */
    protected function cycleCheckpoints($method, UserInterface $user = null, $halt = true)
    {
        
        if (! $this->checkpointsStatus) {
            return true;
        }

        foreach ($this->checkpoints as $checkpoint) {
            $response = $checkpoint->{$method}($user);

            if ($response === false && $halt === true) {
                return false;
            }
        }
        
        return true;
    }
    
    
}
