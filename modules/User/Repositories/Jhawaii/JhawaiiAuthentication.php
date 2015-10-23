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
    
    public function assignRole()
    {
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
