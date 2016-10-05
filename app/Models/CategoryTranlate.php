<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranlate extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category_tranlate';

    public function category()
    {
        return $this->belongsTo('Category');
    }
}
