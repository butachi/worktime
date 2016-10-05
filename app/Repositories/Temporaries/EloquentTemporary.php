<?php

namespace App\Repositories\Temporaries;

use Illuminate\Database\Eloquent\Model;

class EloquentTemporary extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'ho_account_tmp';

    /**
     * Modify create_at and update_at.
     */
    const CREATED_AT = 'created_at_jp';
    const UPDATED_AT = 'updated_at_jp';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'hash',
        'deleted',
        'created_at_hi',
        'updated_at_hi',
    ];

    /**
     * Get mutator for the "deleted" attribute.
     *
     * @param mixed $deleted
     *
     * @return bool
     */
    public function getDeletedAttribute($deleted)
    {
        return (int) $deleted;
    }

    /**
     * Set mutator for the "deleted" attribute.
     *
     * @param mixed $deleted
     */
    public function setCompletedAttribute($deleted)
    {
        $this->attributes['deleted'] = (int) $deleted;
    }
}
