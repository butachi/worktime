<?php

namespace App\Services;

use BadMethodCallException;
use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\Users\UserInterface;
use App\Repositories\Persistences\PersistenceRepositoryInterface;
use App\Repositories\Checkpoints\CheckpointInterface;
use Illuminate\Events\Dispatcher;
use Closure;

class Jhawaii
{
    /**
     * The current cached, logged in user.
     *
     * @var \App\Repositories\Users\UserInterface
     */
    protected $user;

    /**
     * The User repository.
     *
     * @var \App\Repositories\Users\UserRepositoryInterface
     */
    protected $users;

    /**
     * The Persistence repository. Use table ho_account_login_log.
     *
     * @var type
     */
    protected $persistences;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Events\Dispatcher
     */
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

    /**
     * Create new Jhawaii instance.
     *
     * @param \App\Repositories\Users\UserRepositoryInterface               $users
     * @param \App\Repositories\Persistences\PersistenceRepositoryInterface $persistence
     * @param Illuminate\Events\Dispatcher                                  $dispatcher
     */
    public function __construct(
        UserRepositoryInterface $users,
        PersistenceRepositoryInterface $persistence,
        Dispatcher $dispatcher = null
    ) {
        $this->users = $users;
        $this->persistences = $persistence;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Checks to see if a user is logged in.
     *
     * @return \App\Repositories\Users\UserInterface|bool
     */
    public function check()
    {
        if ($this->user !== null) {
            return $this->user;
        }
        if (!$code = $this->persistences->check()) {
            return false;
        }

        if (!$user = $this->persistences->findUserByPersistenceCode($code)) {
            return false;
        }

        if (!$this->cycleCheckpoints('check', $user)) {
            return false;
        }
        return $this->user = $user;
    }
    /**
     * Authenticates a user, with "remember" flag.
     *
     * @param \App\Repositories\Users\UserInterface | array $credentials
     * @param bool                                          $remember
     * @param boll                                          $login
     *
     * @return \App\Repositories\Users\UserInterface|bool
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

        if (!$this->cycleCheckpoints('login', $user)) {
            return false;
        }

        if ($login === true) {
            $method = $remember === true ? 'loginAndRemember' : 'login';

            if (!$user = $this->{$method}($user)) {
                return false;
            }
        }
        $this->dispatcher->fire('jhawaii.authenticated', $user);

        return $this->user = $user;
    }
    /**
     * Returns the currently logged in user, lazily checking for it.
     *
     * @param bool $check
     *
     * @return \App\Repositories\Users\UserInterface
     */
    public function getUser($check = true)
    {
        if ($check === true && $this->user === null) {
            $this->check();
        }

        return $this->user;
    }

    /**
     * Sets the user associated with Jhawaii (does not log in).
     *
     * @param \App\Repositories\Users\UserInterface $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Returns the user repository.
     *
     * @return \App\Repositories\Users\UserRepositoryInterface
     */
    public function getUserRepository()
    {
        return $this->users;
    }

    /**
     * Sets the closure which resolves the request credentials.
     *
     * @param \Closure $requestCredentials
     */
    public function setRequestCredentials(Closure $requestCredentials)
    {
        $this->requestCredentials = $requestCredentials;
    }

    /**
     * Sets the callback which creates a basic response.
     *
     * @param Closure $basicResponse
     */
    public function creatingBasicResponse(Closure $basicResponse)
    {
        $this->basicResponse = $basicResponse;
    }

    /**
     * Persists a login for the given user.
     *
     * @param \App\Repositories\Users\UserInterface $user
     * @param bool                                  $remember
     *
     * @return \App\Repositories\Users\UserInterface|bool
     */
    public function login(UserInterface $user, $remember = false)
    {
        $method = $remember === true ? 'persistAndRemember' : 'persist';

        $this->persistences->{$method}($user);
        // Record login
        $response = $this->users->recordLogin($user);

        if ($response === false) {
            return false;
        }

        return $this->user = $user;
    }

    /**
     * Persists a login for the given user, with the "remember" flag.
     *
     * @param \App\Repositories\Users\UserInterface $user
     *
     * @return \App\Repositories\Users\UserInterface|bool
     */
    public function loginAndRemember(UserInterface $user)
    {
        return $this->login($user, true);
    }

    /**
     * Logs the current user out.
     *
     * @param \App\Repositories\Users\UserInterface $user
     * @param bool                                  $everywhere
     *
     * @return bool
     */
    public function logout(UserInterface $user = null, $everywhere = false)
    {
        $currentUser = $this->check();
        if ($user && $user !== $currentUser) {
            $this->persistences->flush($user, false);

            return true;
        }

        $user = $user ?: $currentUser;

        if ($user === false) {
            return true;
        }

        $method = $everywhere === true ? 'flush' : 'forget';

        $this->persistences->{$method}($user);

        $this->user = null;

        return $this->users->recordLogout($user);
    }

    /**
     * Add a new checkpoint to Sentinel.
     *
     * @param string                                            $key
     * @param \App\Repositories\Checkpoints\CheckpointInterface $checkpoint
     */
    public function addCheckpoint($key, CheckpointInterface $checkpoint)
    {
        $this->checkpoints[$key] = $checkpoint;
    }

    /**
     * Cycles through all the registered checkpoints for a user. Checkpoints
     * may throw their own exceptions, however, if just one returns false,
     * the cycle fails.
     *
     * @param string                                $method
     * @param \App\Repositories\Users\UserInterface $user
     * @param bool                                  $halt
     *
     * @return bool
     */
    protected function cycleCheckpoints($method, UserInterface $user = null, $halt = true)
    {
        if (!$this->checkpointsStatus) {
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
    /**
     * Returns all accessible methods on the associated user repository.
     *
     * @return array
     */
    protected function getUserMethods()
    {
        if (empty($this->userMethods)) {
            $users = $this->getUserRepository();

            $methods = get_class_methods($users);

            $this->userMethods = array_diff($methods, ['__construct']);
        }

        return $this->userMethods;
    }
    /**
     * Dynamically pass missing methods to Jhawaii.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        $methods = $this->getUserMethods();

        if (in_array($method, $methods)) {
            $users = $this->getUserRepository();

            return call_user_func_array([$users, $method], $parameters);
        }

        if (starts_with($method, 'findUserBy')) {
            $user = $this->getUserRepository();

            $method = 'findBy'.substr($method, 10);

            return call_user_func_array([$user, $method], $parameters);
        }

        throw new BadMethodCallException("Call to undefined method {$user}::{$method}()");
    }
}
