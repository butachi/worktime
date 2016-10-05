<?php

namespace App\Models;

use App\Models\BaseModel;

class Ho_ContactList extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ho_contact_list';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'send_to', 'contents', 'email', 'name', 'start_id'];
}
