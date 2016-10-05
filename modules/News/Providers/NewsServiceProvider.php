<?php namespace Modules\News\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\News\Entities\News;
use Modules\News\Repositories\Cache\CacheNewsDecorator;
use Modules\News\Repositories\Eloquent\EloquentNewsRepository;

class NewsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Boot the application events.
	 * 
	 * @return void
	 */
	public function boot()
	{

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{		
		$this->registerBindings();
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

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\News\Repositories\NewsRepository',
            function () {
                $repository = new EloquentNewsRepository(new News());

                if (! Config::get('app.cache')) {
                    return $repository;
                }

                return new CacheNewsDecorator($repository);
            }
        );
    }

}
