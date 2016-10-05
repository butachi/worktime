<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Cart;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app['cart'] = $this->app->share(function ($app) {
            $session = $app['session'];
            $events = $app['events'];

            return new Cart($session, $events);
        });
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
