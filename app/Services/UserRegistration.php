<?php

namespace App\Services;

use App\Contracts\Authentication;
use App\Events\UserHasCompleteRegisterProcess;

class UserRegistration
{
    private $auth;

    private $input;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function register($input)
    {
        $this->input = $input;

        $user = $this->createUser();

        event(new UserHasCompleteRegisterProcess($user));

        return $user;
    }

    private function createUser()
    {
        return $this->auth->register((array) $this->input);
    }
}
