<?php namespace Modules\Core\Contracts;

interface Authentication
{
    public function login();
    
    public function register();
    
    public function active();
    
    public function assignRole();
    
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
