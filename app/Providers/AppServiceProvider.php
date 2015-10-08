<?php

namespace app\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
     
        // Use debugbar with evn local
        if ($this->app->environment() == 'local') {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }
    }
}
