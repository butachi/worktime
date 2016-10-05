<?php namespace modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    
    protected $fillable = ['slug', 'name', 'permissions'];
    
    public function users()
    {
        $this->belongsToMany('Modules\User\Entities\User', 'role_users');
    }
}
