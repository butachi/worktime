<?php

namespace App\Repositories\Checkpoints;

use App\Repositories\Users\UserInterface;

trait AuthenticatedCheckpoint
{
    /**
     * {@inheritdoc}
     */
    public function fail(UserInterface $user = null)
    {
    }
}
