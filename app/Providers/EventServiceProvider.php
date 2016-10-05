<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserHasCompleteRegisterProcess' => [
            'App\Listeners\SendRegisterCompleteEmail',
        ],
        'App\Events\UserHasBegunRegisterProcess' => [
            'App\Listeners\SendRegisterHashEmail',
        ],
        'App\Events\UserHasBegunResetProcess' => [
            'App\Listeners\SendResetHashEmail',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
    }
}
