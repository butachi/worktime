<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The filters base class name.
     *
     * @var array
     */
    protected $middleware = [
        //middleware guest
        'auth.guest' => 'GuestMiddleware',
        'localizationRedirect' => 'LocalizationMiddleware',
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->registerMiddleware($this->app['router']);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->setLocalesConfigurations();

        if ($this->app->environment() == 'local') {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }
    }

    /**
     * Register the filters.
     *
     * @param Router $router
     */
    public function registerMiddleware(Router $router)
    {
        foreach ($this->middleware as $name => $middleware) {
            $class = "App\\Http\\Middleware\\{$middleware}";
            $router->middleware($name, $class);
        }
    }

    /**
     * Set the locale configuration for
     * - laravel localization
     * - laravel translatable.
     */
    private function setLocalesConfigurations()
    {
        $availableLocales = config('hawaii.available-locales');
        $laravelDefaultLocale = $this->app->config->get('app.locale');
        if (!in_array($laravelDefaultLocale, array_keys($availableLocales))) {
            $this->app->config->set('app.locale', array_keys($availableLocales)[0]);
        }
        $this->app->config->set('laravellocalization.supportedLocales', $availableLocales);
        $this->app->config->set('translatable.locales', array_keys($availableLocales));
    }
}
