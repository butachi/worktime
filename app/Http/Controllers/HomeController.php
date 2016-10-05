<?php

namespace app\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Session;

class HomeController extends BaseController
{
    public function index()
    {
        //echo "<pre>";print_r(Session::all());die;
        return view('frontend.home.index');
    }
}
