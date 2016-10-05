<?php

namespace App\Repositories\Reminders;

use Illuminate\Database\Eloquent\Model;

class EloquentReminder extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'ho_account_password_forget';

    /**
     * Modify create_at and update_at.
     */
    const CREATED_AT = 'created_at_jp';
    const UPDATED_AT = 'updated_at_jp';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'ip_addr',
        'user_agent',
        'deleted',
        'created_at_hi',
        'updated_at_hi',
    ];

    /**
     * Get mutator for the "completed" attribute.
     *
     * @param mixed $completed
     *
     * @return bool
     */
    public function getDeletedAttribute($completed)
    {
        return (int) $completed;
    }

    /**
     * Set mutator for the "completed" attribute.
     *
     * @param mixed $completed
     */
    public function setDeletedAttribute($completed)
    {
        $this->attributes['deleted'] = (int) $completed;
    }
}
