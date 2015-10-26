<?php namespace modules\User\Http\Controllers;

use Modules\Core\Http\Controllers\PublicController;
use Modules\User\Http\Requests\RegisterRequest;

class AuthController extends PublicController
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
        app('Modules\User\Services\UserRegistration')->register($request->all());
        \Session::flash('flash_msg', trans('user::messages.account created check email for activation'));
        return redirect()->route('register');        
    }
}
