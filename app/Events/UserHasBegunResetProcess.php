<?php

namespace App\Events;

class UserHasBegunResetProcess
{
    public $user;
    public $hash;

    public function __construct($user, $hash)
    {
        $this->user = $user;
        $this->hash = $hash;
    }
}
