<?php namespace Modules\User\Repositories;

interface UserRepository
{
    /**
     * Returns all the users
     * @return object
     */
    public function all();
}