<?php

namespace App\Repositories\Persistences;

use Illuminate\Database\Eloquent\Model;

class EloquentPersistence extends Model implements PersistenceInterface
{
    protected $table = 'ho_account_login_log';

    public $timestamps = false;
    /**
     * The users model name.
     *
     * @var string
     */
    protected static $usersModel = 'App\Repositories\Users\EloquentUser';

    /**
     * {@inheritdoc}
     */
    public function user()
    {
        return $this->belongsTo(static::$usersModel, 'ho_account_id');
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
     * @param string $usersModel
     */
    public static function setUsersModel($usersModel)
    {
        static::$usersModel = $usersModel;
    }
}
