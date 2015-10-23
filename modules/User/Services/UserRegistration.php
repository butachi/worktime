<?php namespace Modules\User\Services;

use Modules\Core\Contracts\Authentication;
use Modules\User\Repositories\Roles\RoleRepositoryInterface;

class UserRegistration
{
    private $auth;
    
    private $role;
    
    private $input;
    
    public function __construct(Authentication $auth, RoleRepositoryInterface $role)
    {
        $this->auth = $auth;
        $this->role = $role;
    }
    
    public function register($input)
    {
        
        $this->input = $input;
        
        $user = $this->createUser();
        var_dump($user);die;
        if ($this->hasProfileData()) {
            $this->createProfileForUser($user);
        }

        $this->assignUserToUsersGroup($user);

        event(new UserHasRegistered($user));

        return $user;
    }
    
    public function createUser()
    {
        return $this->auth->register((array) $this->input);
    }
}
