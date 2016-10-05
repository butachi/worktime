<?php

namespace App\Repositories\Reminders;

use Carbon\Carbon;
use App\Repositories\Users\UserInterface;
use App\Repositories\Users\UserRepositoryInterface;

class IlluminateReminderRepository implements ReminderRepositoryInterface
{
    /**
     * The user repository.
     *
     * @var \App\Repositories\Users\UserRepositoryInterface
     */
    protected $users;

    /**
     * The Eloquent reminder model name.
     *
     * @var string
     */
    protected $model = 'App\Repositories\Reminders\EloquentReminder';

    /**
     * The expiration time in seconds. default 1days.
     *
     * @var int
     */
    protected $expires = 86400;

    /**
     * The ip address access the system.
     *
     * @var string
     */
    protected $ip_address;

    /**
     * The user agent access the system.
     *
     * @var string
     */
    protected $user_agent;

    /**
     * Create a new Illuminate reminder repository.
     *
     * @param \App\Repositories\Users\UserRepositoryInterface $users
     * @param string                                          $model
     * @param int                                             $expires
     */
    public function __construct(
        UserRepositoryInterface $users,
        $model = null,
        $ip_address = null,
        $user_agent = null,
        $expires = null
    ) {
        $this->users = $users;
        if (isset($model)) {
            $this->model = $model;
        }
        if (isset($expires)) {
            $this->expires = $expires;
        }
        $this->ip_address = $ip_address;
        $this->user_agent = $user_agent;
    }

    /**
     * {@inheritdoc}
     */
    public function create(UserInterface $user)
    {
        $reminder = $this->model;

        $reminder->fill([
            'ip_addr' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'deleted' => 0,
            'created_at_hi' => Carbon::now()->timezone('Pacific/Honolulu'),
            'updated_at_hi' => Carbon::now()->timezone('Pacific/Honolulu'),
        ]);

        $reminder->ho_account_id = $user->getUserId();
        $reminder->save();

        return $reminder;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(UserInterface $user)
    {
        $expires = $this->expires();

        $reminder = $this
            ->model
            ->newQuery()
            ->where('ho_account_id', $user->getUserId())
            ->where('deleted', 0)
            ->where('created_at_jp', '>', $expires);

        return $reminder->first() ?: false;
    }

    /**
     * {@inheritdoc}
     */
    public function complete(UserInterface $user, $password)
    {
        $expire = $this->expires();

        $reminder = $this
            ->model
            ->newQuery()
            ->where('ho_account_id', $user->getUserId())
            ->where('deleted', 0)
            ->where('created_at_jp', '>', $expire)
            ->first();

        if ($reminder === null) {
            return false;
        }

        $credentials = compact('password');

        $valid = $this->users->validForUpdate($user, $credentials);

        if ($valid === false) {
            return false;
        }
        $this->users->update($user, $credentials);

        $reminder->fill([
            'ip_addr' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'deleted' => 1,
            'updated_at_hi' => Carbon::now()->timezone('Pacific/Honolulu'),
        ]);

        $reminder->save();

        return true;
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
}
