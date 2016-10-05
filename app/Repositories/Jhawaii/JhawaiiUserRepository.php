<?php

namespace App\Repositories\Jhawaii;

use App\Facades\Jhawaii;
use Illuminate\Support\Facades\Hash;
use App\Events\UserWasUpdated;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository;

class JhawaiiUserRepository implements UserRepository
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    public function __construct()
    {
        $this->user = Jhawaii::getUserRepository()->getModel();
    }

    /**
     * Returns all the users.
     *
     * @return object
     */
    public function all()
    {
        return $this->user->all();
    }

    /**
     * Create a user resource.
     *
     * @param $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->user->create((array) $data);
    }

    /**
     * Find a user by its ID.
     *
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->user->find($id);
    }

    public function findByRoleId($id)
    {
        return $this->user->findByRoleId($id);
    }

    /**
     * Update a user.
     *
     * @param $user
     * @param $data
     *
     * @return mixed
     */
    public function update($user, $data)
    {
        $user = $user->update($data);

        event(new UserWasUpdated($user));

        return $user;
    }

    /**
     * Deletes a user.
     *
     * @param $id
     *
     * @throws UserNotFoundException
     *
     * @return mixed
     */
    public function delete($id)
    {
        if ($user = $this->user->find($id)) {
            return $user->delete();
        };

        throw new UserNotFoundException();
    }

    /**
     * Find a user by its credentials.
     *
     * @param array $credentials
     *
     * @return mixed
     */
    public function findByCredentials(array $credentials)
    {
        return Jhawaii::findByCredentials($credentials);
    }

    /**
     * Hash the password key.
     *
     * @param array $data
     */
    private function hashPassword(array &$data)
    {
        $data['password'] = Hash::make($data['password']);
    }

    /**
     * Check if there is a new password given
     * If not, unset the password field.
     *
     * @param array $data
     */
    private function checkForNewPassword(array &$data)
    {
        if (!$data['password']) {
            unset($data['password']);

            return;
        }

        $data['password'] = Hash::make($data['password']);
    }
}
