<?php

namespace App\Contracts;

interface Authentication
{
    /**
     * Authenticate a user.
     *
     * @param array $credentials
     * @param bool  $remember    Remember user
     *
     * @return mixed
     */
    public function login(array $credentials, $remember = false);

    /**
     * Register a user.
     *
     * @param array $user
     *
     * @return bool
     */
    public function register(array $user);

    /**
     * Log the user out of the application.
     *
     * @return bool
     */
    public function logout();

    /**
     * Create a reminders for the given user.
     *
     * @param $user
     *
     * @return mixed
     */
    public function createReminder($user);

    /**
     * Completes the reset password process.
     *
     * @param $user
     * @param string $code
     * @param string $password
     *
     * @return bool
     */
    public function completeResetPassword($user, $code, $password);

    /**
     * Create a reminders code for the given user.
     *
     * @param $email
     *
     * @return mixed
     */
    public function createTemporaryHash($email);

    /**
     * Check exist temporary with the hash.
     *
     * @param string $email
     * @param string $hash
     *
     * @return mixed
     */
    public function checkExistsHash($email, $hash);

    /**
     * Check if the user is logged in.
     *
     * @return mixed
     */
    public function check();

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int
     */
    public function id();
}
