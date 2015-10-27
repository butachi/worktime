<?php namespace Modules\User\Repositories\Users;

use Modules\User\Repositories\Persistences\PersistableInterface;
use Illuminate\Database\Eloquent\Model;

class EloquentUser extends Model implements PersistableInterface, UserInterface
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'users';
    
    protected $loginNames = ['email'];
    
    /**
     * {@inheritDoc}
     */
    protected $persistableRelationship = 'persistences';

    /**
     * The Eloquent persistences model name.
     *
     * @var string
     */
    protected static $persistencesModel = 'Modules\User\Repositories\Persistences\EloquentPersistence';
    
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
    
    /**
     * {@inheritDoc}
     */
    protected $persistableKey = 'user_id';
    
    public function getLoginNames()
    {
        return $this->loginNames;
    }
    
    public function getUserLoginName() {
        ;
    }
    
    public function getUserId() {
        ;
    }
    
    public function getUserLogin() {
        ;
    }
    
    public function getUserPassword() {
        ;
    }
    
    public function getPersistableId() {
        return $this->getKey();
    }
    
    /**
     * Returns the persistable key name.
     *
     * @return string
     */
    public function getPersistableKey() {
        return $this->persistableKey;
    }
    /**
     * Returns the persistences relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function persistences()
    {
        return $this->hasMany(static::$persistencesModel, 'user_id');
    }
    
    /**
     * {@inheritDoc}     
     */
    public function getPersistableRelationship() {
        return $this->persistableRelationship;
    }
    
    public function generatePersistenceCode() {
        return str_random(32);
    }
}


