<?php namespace Modules\User\Http\Controllers;

use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\PublicController;
use Modules\User\Http\Requests\LoginRequest;
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

    public function postLogin(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $remember = (bool) $request->get('remember_me', false);
        $error = $this->auth->login($credentials, $remember);
        if (!$error) {
            Flash::success(trans('user::messages.successfully logged in'));

            return redirect()->intended('/');
        }
        Flash::error($error);

        return redirect()->back()->withInput();
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
        Flash::success(trans('user::messages.account created check email for activation'));
        return redirect()->route('register');        
    }
}
