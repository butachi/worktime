<?php namespace Modules\User\Providers;

use Modules\Core\Providers\RouteServiceProvider as CoreRouteServiceProvider;

class RouteServiceProvider extends CoreRouteServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $namespace = 'Modules\User\Http\Controllers';

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function getFrontendRoute()
	{
        return __DIR__ . '/../Http/frontendRoutes.php';
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function getBackendRoute()
	{
        return __DIR__ . '/../Http/backendRoutes.php';
	}

    public function getApiRoute()
    {
        return false;
    }
}
