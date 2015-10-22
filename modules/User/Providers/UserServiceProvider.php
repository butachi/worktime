<?php namespace Modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\User\Repositories\User\EloquentUser;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * @var array
     */
    protected $middleware = [
        'User' => [
            'auth.guest' => 'GuestMiddleware',
            'logged.in' => 'LoggedInMiddleware'
        ],
    ];
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMiddleware($this->app['router']);
        
        $this->publishes([
            __DIR__ . '/../Resources/views' => base_path('resources/views/jh/user'),
        ]);
        $this->loadViewsFrom(base_path('resources/views/jh/user'), 'user');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'user');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {        
        $this->app->singleton('Modules\User\Repositories\User\UserRepository', function($app) {
            return new EloquentUser();
        });
        $this->app->bind('Modules\Core\Contracts\Authentication', 'Modules\User\Repositories\Jh\JhAuthentication');
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
    
    private function registerMiddleware($router)
    {
        foreach ($this->middleware as $module => $middlewares) {
            foreach ($middlewares as $name => $middleware) {
                $class = "Modules\\{$module}\\Http\\Middleware\\{$middleware}";

                $router->middleware($name, $class);
            }
        }
    }
}
