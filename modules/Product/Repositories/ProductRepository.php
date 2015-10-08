<?php namespace Modules\Product\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface ProductRepository extends BaseRepository
{
    /**
     * Find the page set as homepage
     * @return object
     */
    public function findHomepage();

    /**
     * Count all records
     * @return int
     */
    public function countAll();
}
