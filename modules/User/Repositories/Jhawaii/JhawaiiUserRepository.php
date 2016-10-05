<?php namespace Modules\User\Repositories\Jhawaii;

use Modules\User\Facades\Jhawaii;
use Modules\User\Repositories\UserRepository;

class JhawaiiUserRepository implements UserRepository
{
    /**
     * @var \Modules\User\Entities\Sentinel\User
     */
    protected $user;
    /**
     * @var \Cartalyst\Sentinel\Roles\EloquentRole
     */
    protected $role;

    public function __construct()
    {
        $this->user = Jhawaii::getUserRepository()->getModule();
        $this->role = Jhawaii::getRoleRepository()->getModule();
    }

    /**
     * Returns all the users
     * @return object
     */
    public function all()
    {
        return $this->user->all();
    }
}