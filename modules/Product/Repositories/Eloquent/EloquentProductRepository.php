<?php namespace Modules\Product\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Product\Repositories\ProductRepository;

class EloquentProductRepository extends EloquentBaseRepository implements ProductRepository
{
    /**
     * Find the page set as homepage
     * @return object
     */
    public function findHomepage()
    {
        return $this->model->where('is_home', 1)->first();
    }

    /**
     * Count all records
     * @return int
     */
    public function countAll()
    {
        return $this->model->count();
    }
}
