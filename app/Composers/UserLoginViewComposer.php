<?php

namespace App\Composers;

use Illuminate\Contracts\View\View;
use App\Contracts\Authentication;

class UserLoginViewComposer
{
    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function compose(View $view)
    {
        $view->with('user', $this->auth->check());
    }
}
