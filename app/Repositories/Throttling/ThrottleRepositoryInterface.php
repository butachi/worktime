<?php

namespace App\Repositories\Throttling;

use App\Repositories\Users\UserInterface;

interface ThrottleRepositoryInterface
{
    /**
     * Returns the global throttling delay, in seconds.
     *
     * @return int
     */
    public function globalDelay();

    /**
     * Returns the IP address throttling delay, in seconds.
     *
     * @param string $ipAddress
     *
     * @return int
     */
    public function ipDelay($ipAddress);

    /**
     * Returns the throttling delay for the given user, in seconds.
     *
     * @param \App\Repositories\Users\UserInterface $user
     *
     * @return int
     */
    public function userDelay(UserInterface $user);

    /**
     * Logs a new throttling entry.
     *
     * @param string                                $ipAddress
     * @param string                                $userAgent
     * @param string                                $email
     * @param string                                $password
     * @param \App\Repositories\Users\UserInterface $user
     */
    public function log($ipAddress = null, $userAgent = null, $email = null, $password = null, UserInterface $user = null);
}
