<?php

namespace App\Repositories\Throttling;

use Illuminate\Database\Eloquent\Model;

class EloquentThrottle extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'ho_account_login_false';

    /**
     * Modify create_at and update_at.
     */
    const CREATED_AT = 'created_at_jp';
    const UPDATED_AT = 'updated_at_jp';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'email',
        'password',
        'ip_addr',
        'user_agent',
    ];
}
