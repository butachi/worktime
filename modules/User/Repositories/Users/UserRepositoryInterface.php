<?php namespace Modules\User\Repositories\Users;

interface UserRepositoryInterface
{
    /**
     * Finds a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Modules\User\Repositories\Users\UserInterface
     */
    public function findByCredentials(array $credentials);
}

