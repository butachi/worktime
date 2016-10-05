<?php namespace Modules\User\Repositories\Roles;

use Illuminate\Database\Eloquent\Model;

class EloquentRole extends Model
{
    protected $table = 'roles';
    
    protected $fillable = [
        'name',
        'slug',
        'permissions'
    ];
    
    protected static $userModel = 'Modules\User\Repositories\Users\EloquentUser';
    
    /**
     * The users relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany 
     */
    public function users()
    {
        return $this->belongsToMany(static::$userModel, 'role_users', 'role_id', 'user_id')->withTimestamps();
    }
}
