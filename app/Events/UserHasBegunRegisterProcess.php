<?php

namespace App\Events;

class UserHasBegunRegisterProcess
{
    public $temporary;

    public function __construct($temporary)
    {
        $this->temporary = $temporary;
    }
}
