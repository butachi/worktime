<?php namespace Modules\Core\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

abstract class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);
    }

    /**
     * @return string
     */
    abstract protected function getFrontendRoute();

    /**
     * @return string
     */
    abstract protected function getBackendRoute();

    /**
     * @return string
     */
    abstract protected function getApiRoute();

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {
            $this->loadApiRoutes($router);
        });

        $router->group(['namespace' => $this->namespace, 'prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localizationRedirect'] ], function (Router $router) {
            $this->loadBackendRoutes($router);
            $this->loadFrontendRoutes($router);
        });
    }

    /**
     * @param Router $router
     */
    private function loadFrontendRoutes(Router $router)
    {
        $frontend = $this->getFrontendRoute();

        if ($frontend && file_exists($frontend)) {
            $router->group(['middleware' => config('jh.core.core.middleware.frontend', [])], function (Router $router) use ($frontend) {
                require $frontend;
            });
        }
    }

    /**
     * @param Router $router
     */
    private function loadBackendRoutes(Router $router)
    {
        $backend = $this->getBackendRoute();

        if ($backend && file_exists($backend)) {
            $router->group(['namespace' => 'Admin', 'prefix' => config('jh.core.core.admin-prefix'), 'middleware' => config('asgard.core.core.middleware.backend', [])], function (Router $router) use ($backend) {
                require $backend;
            });
        }
    }

    /**
     * @param Router $router
     */
    private function loadApiRoutes(Router $router)
    {
        $api = $this->getApiRoute();

        if ($api && file_exists($api)) {
            $router->group(['namespace' => 'Api', 'prefix' => 'api', 'middleware' => config('jh.core.core.middleware.api', [])], function (Router $router) use ($api) {
                require $api;
            });
        }
    }
}