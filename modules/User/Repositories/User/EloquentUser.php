<?php namespace Modules\User\Repositories\User;

use Illuminate\Database\Eloquent\Model;

class EloquentUser extends Model implements UserRepository
{
    public function get()
    {        
        return (User::with('roles')->get());
    }
}


