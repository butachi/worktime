<?php namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class User extends Model implements UserInterface
{
    protected $table = 'users';
    
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'permissions', 'last_login'];
    
    
    public function roles()
    {
        return $this->belongsToMany('Modules\User\Entities\Role', 'role_users', 'role_id', 'user_id')->withTimestamps();
    }

    public function post()
    {
        return $this->hasMany('Modules\Product\Entities\Product');
    }    
}
