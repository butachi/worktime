<?php

namespace app\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        view()->composer(
            [
                'partials.header',
            ],
            'App\Composers\UserLoginViewComposer'
        );
    }
}
