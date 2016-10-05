<?php

namespace App\Repositories\Sessions;

interface SessionRepositoryInterface
{
    public function get();

    public function put($value);

    public function forget();
}
