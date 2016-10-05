<?php
namespace Modules\News\Repositories\Cache;

use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\News\Repositories\NewsRepository;

class CacheNewsDecorator extends BaseCacheDecorator implements NewsRepository
{
    /**
     * @var NewsRepository
     */
    protected $repository;

    public function __construct(NewsRepository $news)
    {
        parent::__construct();
        $this->entityName = 'pages';
        $this->repository = $news;
    }

    /**
     * Find the page set as homepage
     *
     * @return object
     */
    public function homepage()
    {
        //$this->cache->pull("{$this->locale}.{$this->entityName}.homepage");
        return $this->cache
            ->remember("{$this->locale}.{$this->entityName}.homepage", $this->cacheTime,
                function () {
                    return $this->repository->homepage();
                }
            );
    }

    /**
     * Count all records
     * @return int
     */
    public function countAll()
    {
        return $this->cache
            ->tags($this->entityName, 'global')
            ->remember("{$this->locale}.{$this->entityName}.countAll", $this->cacheTime,
                function () {
                    return $this->repository->countAll();
                }
            );
    }
}
