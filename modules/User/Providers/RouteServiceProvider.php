<?php namespace modules\User\Providers;

use Modules\Core\Providers\RoutingServiceProvider;

class RouteServiceProvider extends RoutingServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     * @var string
     */
    protected $namespace = 'Modules\User\Http\Controllers';
    
    protected function getFrontendRoute()
    {
        return __DIR__ . '/../Http/frontendRoutes.php';
    }
    
    protected function getBackendRoute()
    {
        return __DIR__ . '/../Http/backendRoutes.php';
    }

    /**
     * @return string
     */
    protected function getApiRoute()
    {
        return false;
    }
}
