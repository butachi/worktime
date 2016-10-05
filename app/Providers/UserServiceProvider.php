<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->garbageCollect();
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->registerPersistence();
        $this->registerUsers();
        $this->registerCheckpoints();
        $this->registerTemporary();
        $this->registerReminder();
        $this->registerJhawaii();
        $this->registerBindings();
        $this->setUserResolver();
    }
    
    public function registerPersistence()
    {
        $this->registerSession();
        $this->registerCookie();

        $this->app->singleton('App\Repositories\Persistences\PersistenceRepositoryInterface', function ($app) {
            return new \App\Repositories\Persistences\IlluminatePersistenceRepository(
                $app['App\Repositories\Sessions\SessionRepositoryInterface'],
                $app['App\Repositories\Cookies\CookieRepositoryInterface'],
                $app['App\Repositories\Persistences\EloquentPersistence'],
                $app['request']->ip(),
                $app['request']->header('User-Agent')
            );
        });
    }

    public function registerSession()
    {
        $this->app->singleton('App\Repositories\Sessions\SessionRepositoryInterface', function ($app) {
            $key = $app['config']->get('session.cookie');

            return new \App\Repositories\Sessions\SessionRepository($app['session.store'], $key);
        });
    }

    public function registerCookie()
    {
        $this->app->singleton('App\Repositories\Cookies\CookieRepositoryInterface', function ($app) {
            $key = $app['config']->get('cookies');

            return new \App\Repositories\Cookies\CookieRepository($app['request'], $app['cookie'], $key);
        });
    }

    public function registerUsers()
    {
        $this->app->singleton('App\Repositories\Users\UserRepositoryInterface', function ($app) {
            return new \App\Repositories\Users\IlluminateUserRepository(
                $app['App\Repositories\Users\EloquentUser'],
                $app['events']
            );
        });
    }

    /**
     * Registers the checkpoints.
     *
     * @throws \InvalidArgumentException
     */
    protected function registerCheckpoints()
    {
        $this->registerThrottleCheckpoint();
        $this->app->singleton('checkpoints', function ($app) {
            $activeCheckpoints = $app['config']->get('hwo.checkpoints');

            $checkpoints = [];

            foreach ($activeCheckpoints as $checkpoint) {
                if (!$app->offsetExists("checkpoint.{$checkpoint}")) {
                    throw new InvalidArgumentException("Invalid checkpoint [$checkpoint] given.");
                }

                $checkpoints[$checkpoint] = $app["checkpoint.{$checkpoint}"];
            }

            return $checkpoints;
        });
    }

    /**
     * Registers the throttle checkpoint.
     */
    protected function registerThrottleCheckpoint()
    {
        $this->registerThrottling();
        $this->app->singleton('checkpoint.throttle', function ($app) {
            return new \App\Repositories\Checkpoints\ThrottleCheckpoint(
                $app['throttling'],
                $app['request']->getClientIp(),
                $app['request']->header('User-Agent'),
                $app['request']->email,
                $app['request']->password
            );
        });
    }

    /**
     * Registers the throttle.
     */
    protected function registerThrottling()
    {
        $this->app->singleton('throttling', function ($app) {
            foreach (['global', 'ip', 'user'] as $type) {
                ${"{$type}Interval"} = $app['config']->get("hwo.throttling.{$type}.interval");
                ${"{$type}Thresholds"} = $app['config']->get("hwo.throttling.{$type}.thresholds");
            }

            return new \App\Repositories\Throttling\IlluminateThrottleRepository(
                $app['App\Repositories\Throttling\EloquentThrottle'],
                $globalInterval,
                $globalThresholds,
                $ipInterval,
                $ipThresholds,
                $userInterval,
                $userThresholds
            );
        });
    }

    public function registerTemporary()
    {
        $this->app->singleton('temporary', function ($app) {
            $config = $app['config']->get('hwo');
            $expires = array_get($config, 'temporary.expires');

            return new \App\Repositories\Temporaries\IlluminateTemporaryRepository(
                $app['App\Repositories\Users\UserRepositoryInterface'],
                $app['App\Repositories\Temporaries\EloquentTemporary'],
                $expires
            );
        });
    }

    public function registerReminder()
    {
        $this->app->singleton('reminder', function ($app) {
            $config = $app['config']->get('hwo');
            $expires = array_get($config, 'reminders.expires');

            return new \App\Repositories\Reminders\IlluminateReminderRepository(
                $app['App\Repositories\Users\UserRepositoryInterface'],
                $app['App\Repositories\Reminders\EloquentReminder'],
                $app['request']->ip(),
                $app['request']->header('User-Agent'),
                $expires
            );
        });
    }

    public function registerJhawaii()
    {
        //register jhawaii
        $this->app->singleton('jhawaii', function ($app) {
            $hawaiioption = new \App\Services\Jhawaii(
                $app['App\Repositories\Users\UserRepositoryInterface'],
                $app['App\Repositories\Persistences\PersistenceRepositoryInterface'],
                $app['events']
            );

            if (isset($app['checkpoints'])) {
                foreach ($app['checkpoints'] as $key => $checkpoint) {
                    $hawaiioption->addCheckpoint($key, $checkpoint);
                }
            }

            $hawaiioption->setRequestCredentials(function () use ($app) {
                $request = $app['request'];

                $login = $request->getUser();
                $password = $request->getPassword();

                if ($login === null && $password === null) {
                    return;
                }

                return compact('login', 'password');
            });

            $hawaiioption->creatingBasicResponse(function () {
                $headers = ['WWW-Authenticate' => 'Basic'];

                return new Response('Invalid credentials.', 401, $headers);
            });

            return $hawaiioption;
        });

        $this->app->alias('Jhawaii', 'App\Facades\Jhawaii');
    }

    /**
     * Register the bindings.
     */
    public function registerBindings()
    {
        $this->app->bind(
            'App\Repositories\UserRepository',
            'App\Repositories\Jhawaii\JhawaiiUserRepository'
        );

        $this->app->bind(
            'App\Contracts\Authentication',
            'App\Repositories\Jhawaii\JhawaiiAuthentication'
        );
    }

    /**
     * Garbage collect activations and reminders.
     */
    protected function garbageCollect()
    {
        $config = $this->app['config']->get('hwo.temporary.lottery');

        $this->sweep($this->app['temporary'], $config);
    }

    /**
     * Sweep expired codes.
     *
     * @param mixed $repository
     * @param array $lottery
     */
    protected function sweep($repository, $lottery)
    {
        if ($this->configHitsLottery($lottery)) {
            try {
                $repository->removeExpired();
            } catch (Exception $e) {
            }
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     *
     * @param array $lottery
     *
     * @return bool
     */
    protected function configHitsLottery(array $lottery)
    {
        return random_int(1, $lottery[1]) <= $lottery[0];
    }

    /**
     * Sets the user resolver on the request class.
     */
    protected function setUserResolver()
    {
        $this->app['request']->setUserResolver(function () {
            return $this->app['jhawaii']->getUser();
        });
    }
}
