<?php namespace Modules\Core\Contracts;

interface Authentication
{
    /**
     * Authenticate a user
     * @param array $credentials
     * @param bool $remember        Remember user
     * @return mixed
     */
    public function login(array $credentials, $remember = false);
    
    public function register(array $user);
    
    public function active();
    
    /**
     * Assign a role to the given user
     * @param \Modules\User\Repositories\Users\UserRepository $user
     * @param \Modules\User\Repositories\Roles\RoleRepository $role
     * @return mixed
     */
    public function assignRole($user, $role);
    
    public function logout();
    
    /**
     * Check if the user is logged in
     * @return mixed
     */
    public function check();

    /**
     * Get the ID for the currently authenticated user
     * @return int
     */
    public function id();
}
