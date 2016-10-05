<?php

namespace App\Events;

class UserHasCompleteRegisterProcess
{
    public $temporary;

    public function __construct($temporary)
    {
        $this->temporary = $temporary;
    }
}
