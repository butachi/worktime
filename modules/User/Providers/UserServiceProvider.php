<?php namespace modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\User\Services\Jhawaii;

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
        $this->registerPersistence();
        $this->registerUsers();
        $this->registerRoles();
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
    
    public function registerPersistence()
    {
        $this->registerSession();
        $this->registerCookie();
        
        $this->app->singleton('Modules\User\Repositories\Persistences\PersistenceRepositoryInterface', function ($app) {
            return new \Modules\User\Repositories\Persistences\PersistenceRepository(
                    $app['Modules\User\Repositories\Sessions\SessionRepositoryInterface'],
                    $app['Modules\User\Repositories\Cookies\CookieRepositoryInterface']
            );
        });
    }
    
    public function registerSession()
    {
        $this->app->singleton('Modules\User\Repositories\Sessions\SessionRepositoryInterface', function ($app) {
            $key = $app['config']->get('session.cookie');
            return new \Modules\User\Repositories\Sessions\SessionRepository($app['session.store'], $key);
        });
    }
    
    public function registerCookie()
    {
        $this->app->singleton('Modules\User\Repositories\Cookies\CookieRepositoryInterface', function ($app) {
            $key = $app['config']->get('cookies');
            return new \Modules\User\Repositories\Cookies\CookieRepository($app['request'], $app['cookie'], $key);
        });
    }
    
    public function registerUsers()
    {
        $this->app->singleton('Modules\User\Repositories\Users\UserRepositoryInterface', function ($app) {
            return new \Modules\User\Repositories\Users\UserRepository(
                    $app['Modules\User\Repositories\Users\EloquentUser'], 
                    $app['events']
            );
        });
    }
    
    public function registerJhawaii()
    {
        //register jhawaii
        $this->app->singleton('jhawaii', function ($app) {
            return new Jhawaii(
                $app['Modules\User\Repositories\Users\UserRepositoryInterface'],
                $app['Modules\User\Repositories\Persistences\PersistenceRepositoryInterface']
            );
        });
        
        $this->app->alias('Jhawaii', 'Modules\User\Facades\Jhawaii');
    }
    
    public function registerRoles()
    {
        $this->app->singleton('Modules\User\Repositories\Roles\RoleRepositoryInterface', function($app) {
            return new \Modules\User\Repositories\Roles\RoleRepository(
                    $app['Modules\User\Repositories\Roles\EloquentRole']
            );
        });
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
