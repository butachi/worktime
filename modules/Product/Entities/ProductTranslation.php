<?php namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $table = 'page__page_translations';
    protected $fillable = [
        'page_id',
        'title',
        'slug',
        'status',
        'body',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
    ];
}
