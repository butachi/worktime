<?php namespace Modules\User\Repositories\User;

use Modules\User\Entities\User as User;

class EloquentUser implements UserRepository
{
    public function get()
    {
        return (User::with('roles')->get());
    }
}


