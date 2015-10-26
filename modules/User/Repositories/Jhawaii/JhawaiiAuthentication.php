<?php namespace Modules\User\Repositories\Jhawaii;

use Modules\Core\Contracts\Authentication;
use Modules\User\Facades\Jhawaii;

class JhawaiiAuthentication implements Authentication
{
    public function login()
    {
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
        Jhawaii::check();
    }
    
    public function id()
    {
    }
}
