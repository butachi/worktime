<?php namespace modules\Product\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Product\Entities\Product;
use Modules\Product\Repositories\Cache\CachePageDecorator;
use Modules\Product\Repositories\Eloquent\EloquentProductRepository;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
    }
    
    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Product\Repositories\ProductRepository',
            function () {
                $repository = new EloquentProductRepository(new Product());

                if (! Config::get('app.cache')) {
                    return $repository;
                }

                return new CachePageDecorator($repository);
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
