<?php namespace Modules\News\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface NewsRepository extends BaseRepository
{
    /**
     * Get home page
     * @return object
     */
    public function homepage();

    /**
     * Count all records
     * @return int
     */
    public function countAll();
}
