<?php namespace modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Pingpong\Modules\Routing\Controller;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function index()
    {
        return view('user::index');
    }
    
    public function getLogin()
    {
        return view('user::public.login');
    }
    
    
    public function postLogin()
    {
    }
    
    public function getReset()
    {
        return view('user::public.reset');
    }
    
    public function postReset()
    {
    }
    
    public function getRegister()
    {
        return view('user::public.register');
    }
    
    public function postRegister(RegisterRequest $request)
    {
        $input = $request->all();
        
        
        User::create($input);
        
        return $input;
    }
}
