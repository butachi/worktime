<?php

namespace App\Repositories;

/**
 * Interface UserRepository.
 */
interface UserRepository
{
    /**
     * Returns all the users.
     *
     * @return object
     */
    public function all();

    /**
     * Create a user resource.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data);

    /**
     * Find a user by its ID.
     *
     * @param $id
     *
     * @return mixed
     */
    public function find($id);

    /**
     * Update a user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $data);

    /**
     * Deletes a user.
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id);

    /**
     * Find a user by its credentials.
     *
     * @param array $credentials
     *
     * @return mixed
     */
    public function findByCredentials(array $credentials);
}
