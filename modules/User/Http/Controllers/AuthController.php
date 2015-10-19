<?php namespace modules\User\Http\Controllers;

use Modules\Core\Http\Controllers\PublicController;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\RegisterRequest;

class AuthController extends PublicController
{
    public function index()
    {
        return view('user::index');
    }
    
    public function getLogin()
    {
        $users = User::find(1);
        
        foreach ($users->roles as $role) {
            echo $role->pivot->user_id;
        }
        return $users->roles;
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
        
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        
        $user = User::create($input);
        print_r($user);
        die;
        return $input;
    }
}
