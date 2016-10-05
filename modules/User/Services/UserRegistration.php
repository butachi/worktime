<?php namespace Modules\User\Services;

use Modules\Core\Contracts\Authentication;
use Modules\User\Events\UserHasRegistered;
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
        
        if ($this->hasProfileData()) {
            $this->createProfileForUser($user);
        }

        $this->assignUserToUsersGroup($user);

        event(new UserHasRegistered($user));

        return $user;
    }
    
    private function createUser()
    {
        return $this->auth->register((array) $this->input);
    }
    
    private function hasProfileData()
    {
        return isset($this->input['profile']);
    }
    
    private function createProfileForUser($user)
    {
        $profileData = array_merge($this->input['profile'], ['user_id' => $user->id]);
        app('Modules\Profile\Repositories\ProfileRepository')->create($profileData);
    }
    
    private function assignUserToUsersGroup($user)
    {
        $role = $this->role->findByName('User');
        
        $this->auth->assignRole($user, $role);
    }
}
