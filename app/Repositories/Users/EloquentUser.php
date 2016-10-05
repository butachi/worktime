<?php

namespace App\Repositories\Users;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Persistences\PersistableInterface;

class EloquentUser extends Model implements PersistableInterface, UserInterface
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'ho_account';

    public $timestamps = false;
    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'email',
        'password',
        'name_family',
        'name_fore',
        'name_family_eng',
        'name_family_fore',
        'birth',
        'sex',
        'passport_no',
    ];

    /**
     * {@inheritdoc}
     */
    protected $persistableKey = 'ho_account_id';

    /**
     * {@inheritdoc}
     */
    protected $persistableRelationship = 'persistences';

    /**
     * Array of login column names.
     *
     * @var array
     */
    protected $loginNames = ['email'];

    /**
     * The Eloquent persistences model name.
     *
     * @var string
     */
    protected static $persistencesModel = 'App\Repositories\Persistences\EloquentPersistence';

    /**
     * The Eloquent activations model name.
     *
     * @var string
     */
    protected static $activationsModel = 'App\Repositories\Activations\EloquentActivation';

    /**
     * The Eloquent reminders model name.
     *
     * @var string
     */
    protected static $remindersModel = 'App\Repositories\Reminders\EloquentReminder';

    /**
     * The Eloquent throttling model name.
     *
     * @var string
     */
    protected static $throttlingModel = 'App\Repositories\Throttling\EloquentThrottle';

    /**
     * Returns an array of login column names.
     *
     * @return array
     */
    public function getLoginNames()
    {
        return $this->loginNames;
    }

    /**
     * Returns the persistences relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function persistences()
    {
        return $this->hasMany(static::$persistencesModel, 'ho_account_id');
    }

    /**
     * Returns the activations relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activations()
    {
        return $this->hasMany(static::$activationsModel, 'user_id');
    }

    /**
     * Returns the reminders relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reminders()
    {
        return $this->hasMany(static::$remindersModel, 'user_id');
    }

    /**
     * Returns the throttle relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function throttle()
    {
        return $this->hasMany(static::$throttlingModel, 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function generatePersistenceCode()
    {
        return str_random(32);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserId()
    {
        return $this->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistableId()
    {
        return $this->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistableKey()
    {
        return $this->persistableKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setPersistableKey($key)
    {
        $this->persistableKey = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function setPersistableRelationship($persistableRelationship)
    {
        $this->persistableRelationship = $persistableRelationship;
    }

    /**
     * {@inheritdoc}
     */
    public function getPersistableRelationship()
    {
        return $this->persistableRelationship;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserLogin()
    {
        return $this->getAttribute($this->getUserLoginName());
    }

    /**
     * {@inheritdoc}
     */
    public function getUserLoginName()
    {
        return reset($this->loginNames);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserPassword()
    {
        return $this->password;
    }

    /**
     * Returns the persistences model.
     *
     * @return string
     */
    public static function getPersistencesModel()
    {
        return static::$persistencesModel;
    }

    /**
     * Sets the persistences model.
     *
     * @param string $persistencesModel
     */
    public static function setPersistencesModel($persistencesModel)
    {
        static::$persistencesModel = $persistencesModel;
    }

    /**
     * Returns the activations model.
     *
     * @return string
     */
    public static function getActivationsModel()
    {
        return static::$activationsModel;
    }

    /**
     * Sets the activations model.
     *
     * @param string $activationsModel
     */
    public static function setActivationsModel($activationsModel)
    {
        static::$activationsModel = $activationsModel;
    }

    /**
     * Returns the reminders model.
     *
     * @return string
     */
    public static function getRemindersModel()
    {
        return static::$remindersModel;
    }

    /**
     * Sets the reminders model.
     *
     * @param string $remindersModel
     */
    public static function setRemindersModel($remindersModel)
    {
        static::$remindersModel = $remindersModel;
    }

    /**
     * Returns the throttling model.
     *
     * @return string
     */
    public static function getThrottlingModel()
    {
        return static::$throttlingModel;
    }

    /**
     * Sets the throttling model.
     *
     * @param string $throttlingModel
     */
    public static function setThrottlingModel($throttlingModel)
    {
        static::$throttlingModel = $throttlingModel;
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        if ($this->exists) {
            $this->persistences()->delete();
            $this->reminders()->delete();
            $this->throttle()->delete();
        }

        parent::delete();
    }
}
