<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Category extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category';

    public function translation()
    {
        return $this->hasMany('App\Models\CategoryTranlate')->where('language_code', '=', App::getLocale());
    }
}
