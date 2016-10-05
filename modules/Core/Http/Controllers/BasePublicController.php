<?php namespace modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

abstract class BasePublicController extends Controller
{
    protected $auth;
    public $locale;
    
    
    public function __construct()
    {
        $this->locale = App::getLocale();
        $this->auth = app('Modules\Core\Contracts\Authentication');

        view()->share('currentUser', $this->auth->check());
    }
}
