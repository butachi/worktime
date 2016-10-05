<?php

namespace App\Repositories\Temporaries;

use Carbon\Carbon;
use App\Repositories\Users\UserInterface;
use App\Repositories\Users\UserRepositoryInterface;

class IlluminateTemporaryRepository implements TemporaryRepositoryInterface
{
    /**
     * The user repository.
     *
     * @var \App\Repositories\Users\UserRepositoryInterface
     */
    protected $users;

    /**
     * The expiration time in seconds. default 24h.
     *
     * @var int
     */
    protected $expires = 86400;
    /**
     * The Eloquent reminder model name.
     *
     * @var string
     */
    protected $model = 'App\Repositories\Temporaries\EloquentTemp';

    /**
     * Create a new Illuminate reminder repository.
     *
     * @param string $model
     */
    public function __construct(
        UserRepositoryInterface $users,
        $model = null,
        $expire = null
    ) {
        $this->users = $users;

        if (isset($model)) {
            $this->model = $model;
        }
        $this->expires = $expire;
    }

    /**
     * {@inheritdoc}
     */
    public function create($email)
    {
        $temporary = $this->model;
        $hash = $this->generateHash();

        $temporary->fill([
            'email' => $email,
            'hash' => $hash,
            'deleted' => 0,
            'created_at_hi' => Carbon::now()->timezone('Pacific/Honolulu'),
            'updated_at_hi' => Carbon::now()->timezone('Pacific/Honolulu'),
        ]);
        $temporary->email = $email;
        $temporary->save();

        return $temporary;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($email, $code = null)
    {
        $expires = $this->expires();

        $temporary = $this
            ->model
            ->newQuery()
            ->where('email', $email)
            ->where('deleted', false)
            ->where('created_at_jp', '>', $expires);
        if ($code) {
            $temporary->where('hash', $code);
        }

        return $temporary->first() ?: false;
    }

    /**
     * {@inheritdoc}
     */
    public function completeRegister($credentials)
    {
        $expires = $this->expires();

        $temporary = $this
            ->model
            ->newQuery()
            ->where('email', $credentials['email'])
            ->where('hash', $credentials['hash'])
            ->where('deleted', 0)
            ->where('created_at_jp', '>', $expires)
            ->first();

        if ($temporary === null) {
            return false;
        }

        $user = $this->users->create($credentials);

        $temporary->fill([
            'deleted' => 1,
            'ho_account_id' => $user->id,
            'updated_at_hi' => Carbon::now()->timezone('Pacific/Honolulu'),
        ]);
        $temps = $temporary->save();

        return $temps;
    }

    public function completePasswordForget(UserInterface $user, $hash)
    {
        $expires = $this->expires();

        $temporary = $this->model
                ->newQuery()
                ->where('email', $user->getUserLogin())
                ->where('hash', $hash)
                ->where('deleted', 0)
                ->where('created_at_jp', '>', $expires)
                ->first();
        if ($temporary === null) {
            return false;
        }

        $temporary->fill([
            'deleted' => 1,
            'ho_account_id' => $user->getUserId(),
            'updated_at_hi' => Carbon::now()->timezone('Pacific/Honolulu'),
        ]);
        $temps = $temporary->save();

        return $temps;
    }

    /**
     * {@inheritdoc}
     */
    public function removeExpired()
    {
        $expires = $this->expires();

        return $this
            ->model
            ->newQuery()
            ->where('deleted', 0)
            ->where('created_at_jp', '<', $expires)
            ->delete();
    }

    /**
     * Returns the expiration date.
     *
     * @return \Carbon\Carbon
     */
    protected function expires()
    {
        return Carbon::now()->subSeconds($this->expires);
    }

    /**
     * Returns a random string for a reminder code.
     *
     * @return string
     */
    protected function generateHash()
    {
        return str_random(32);
    }
}
