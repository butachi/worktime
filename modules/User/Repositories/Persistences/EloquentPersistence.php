<?php namespace Modules\User\Repositories\Persistences;

use Illuminate\Database\Eloquent\Model;

class EloquentPersistence extends Model implements PersistenceInterface
{
    protected $table = 'persistences';
    
    /**
     * The users model name.
     *
     * @var string
     */
    protected static $usersModel = 'Modules\User\Repositories\Users\EloquentUser';
    
    /**
     * {@inheritDoc}
     */
    public function user()
    {
        return $this->belongsTo(static::$usersModel);
    }
    
    /**
     * Get the users model.
     *
     * @return string
     */
    public static function getUsersModel()
    {
        return static::$usersModel;
    }

    /**
     * Set the users model.
     *
     * @param  string  $usersModel
     * @return void
     */
    public static function setUsersModel($usersModel)
    {
        static::$usersModel = $usersModel;
    }
}
