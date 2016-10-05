<?php

namespace App\Repositories\Users;

interface UserRepositoryInterface
{
    /**
     * Finds a user by the given primary key.
     *
     * @param int $id
     *
     * @return \App\Repositories\Users\UserInterface
     */
    public function findById($id);

    /**
     * Finds a user by the given credentials.
     *
     * @param array $credentials
     *
     * @return \App\Repositories\Users\UserInterface
     */
    public function findByCredentials(array $credentials);

    /**
     * Records a logout for the given user.
     *
     * @param \App\Repositories\Users\UserInterface $user
     *
     * @return \App\Repositories\Users\UserInterface|bool
     */
    public function recordLogout(UserInterface $user);
}
