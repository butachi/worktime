<?php namespace Modules\User\Repositories\Jhawaii;

use Modules\Core\Contracts\Authentication;
use Modules\User\Facades\Jhawaii;

class JhawaiiAuthentication implements Authentication
{
    /**
     * Authenticate a user
     * @param array $credentials
     * @param bool $remember    Remember user
     * @return mixed
     */
    public function login(array $credentials, $remember = false)
    {
        try {
            if (Jhawaii::authenticate($credentials, $remember)) {
                return false;
            }
        } catch (NotActivatedException $e) {
            return 'Account not yet validated. Please check your email.';
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            return "Your account is blocked for {$delay} second(s).";
        }
    }
    
    public function register(array $user)
    {       
        return Jhawaii::getUserRepository()->create((array) $user);
        //return Jhawaii::get();
        //return $this->users->get();
    }
    
    public function active()
    {
    }
    
    /**
     * Assign a role to the given user
     * @param \Modules\User\Repositories\Users\UserRepository $user
     * @param \Modules\User\Repositories\Roles\RoleRepository $role
     * @return mixed
     */
    public function assignRole($user, $role)
    {
        return $role->users()->attach($user);
    }
    
    public function logout()
    {
    }
    
    public function check()
    {
        return Jhawaii::check();
    }
    
    public function id()
    {
    }
}
