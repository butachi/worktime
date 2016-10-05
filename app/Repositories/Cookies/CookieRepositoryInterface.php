<?php

namespace App\Repositories\Cookies;

interface CookieRepositoryInterface
{
    public function get();

    public function put($value);

    public function forget();
}
