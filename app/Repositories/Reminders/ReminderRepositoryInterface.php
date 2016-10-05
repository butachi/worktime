<?php

namespace App\Repositories\Reminders;

use App\Repositories\Users\UserInterface;

interface ReminderRepositoryInterface
{
    /**
     * Create a new reminder record and code.
     *
     * @param \App\Repositories\Users\UserInterface $user
     *
     * @return string
     */
    public function create(UserInterface $user);

    /**
     * Check if a valid reminder exists.
     *
     * @param \App\Repositories\Users\UserInterface $user
     * @param string                                $code
     *
     * @return bool
     */
    public function exists(UserInterface $user);

    /**
     * Complete reminder for the given user.
     *
     * @param \App\Repositories\Users\UserInterface $user
     * @param string                                $code
     * @param string                                $password
     *
     * @return bool
     */
    public function complete(UserInterface $user, $password);

    /**
     * Remove expired reminder codes.
     *
     * @return int
     */
    public function removeExpired();
}
