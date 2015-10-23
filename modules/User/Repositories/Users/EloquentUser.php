<?php namespace Modules\User\Repositories\Users;

use Illuminate\Database\Eloquent\Model;

class EloquentUser extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'users';
    
    protected $loginNames = ['email'];


    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'email',
        'password',
        'last_name',
        'first_name',
        'permissions',
    ];
    
    public function getLoginNames()
    {
        return $this->loginNames;
    }
}


