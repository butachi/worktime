<?php

namespace App\Repositories\Jhawaii;

use App\Repositories\Checkpoints\NotActivatedException;
use App\Repositories\Checkpoints\ThrottlingException;
use App\Contracts\Authentication;
use App\Facades\Jhawaii;
use App\Facades\Reminder;
use App\Facades\Temporary;
use App\Exceptions\InvalidOrExpiredHash;
use App\Events\UserHasCompleteRegisterProcess;

class JhawaiiAuthentication implements Authentication
{
    /**
     * Authenticate a user.
     *
     * @param array $credentials
     * @param bool  $remember    Remember user
     *
     * @return mixed
     */
    public function login(array $credentials, $remember = false)
    {
        try {
            if (Jhawaii::authenticate($credentials, $remember)) {
                return false;
            }

            return 'Invalid login or password.';
        } catch (NotActivatedException $e) {
            return 'Account not yet validated. Please check your email.';
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();

            return "Your account is blocked for {$delay} second(s).";
        }
    }

    /**
     * Register a user.
     *
     * @param array $user
     *
     * @return bool
     */
    public function register(array $user)
    {
        $success = Temporary::completeRegister($user);

        if (!$success) {
            throw new InvalidOrExpiredHash();
        }
        event(new UserHasCompleteRegisterProcess($success));

        return $success;
    }

    /**
     * Log the user out of the application.
     *
     * @return bool
     */
    public function logout()
    {
        return Jhawaii::logout();
    }

    /**
     * Create a reminders for the given user.
     *
     * @param $user
     *
     * @return mixed
     */
    public function createReminder($user)
    {
        $reminder = Reminder::exists($user) ?: Reminder::create($user);

        return $reminder;
    }

    /**
     * Completes the reset password process.
     *
     * @param $user
     * @param string $code
     * @param string $password
     *
     * @return bool
     */
    public function completeResetPassword($user, $code, $password)
    {
        $success = Temporary::completePasswordForget($user, $code);
        if (!$success) {
            throw new InvalidOrExpiredHash();
        }

        return Reminder::complete($user, $password);
    }

    /**
     * Create a reminders code for the given user.
     *
     * @param $email
     *
     * @return mixed
     */
    public function createTemporaryHash($email)
    {
        $temporary = Temporary::exists($email) ?: Temporary::create($email);

        return $temporary;
    }

    /**
     * Check exist temporary with the hash.
     *
     * @param string $email
     * @param string $hash
     *
     * @return mixed
     */
    public function checkExistsHash($email, $hash)
    {
        return Temporary::exists($email, $hash);
    }

    /**
     * Check if the user is logged in.
     *
     * @return mixed
     */
    public function check()
    {
        return Jhawaii::check();
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int
     */
    public function id()
    {
        if (!$user = $this->check()) {
            return;
        }

        return $user->id;
    }
}
