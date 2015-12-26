<?php
namespace Modules\News\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\News\Repositories\NewsRepository;

class EloquentNewsRepository extends EloquentBaseRepository implements NewsRepository
{
    /**
     * Find the page set as homepage
     * @return object
     */
    public function homepage()
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
