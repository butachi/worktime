<?php namespace modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\User\Repositories\User\EloquentUser;
use Modules\User\Classes\Jhawaii;

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
        $this->registerMiddleware($this->app['router']);
        $this->registerUser();
        $this->registerJhawaii();
        $this->app->bind('Modules\Core\Contracts\Authentication', 'Modules\User\Repositories\Jhawaii\JhawaiiAuthentication');
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
    
    public function registerUser()
    {
        $this->app->singleton('Modules\User\Repositories\User\UserRepository', function ($app) {
            return new EloquentUser();
        });
    }
    
    public function registerJhawaii()
    {
        //register jhawaii
        $this->app->singleton('jhawaii', function () {
            return new Jhawaii();
        });
        
        $this->app->alias('Jhawaii', 'Modules\User\Facades\Jhawaii');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];        
    }
}
