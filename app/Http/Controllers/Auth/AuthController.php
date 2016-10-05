<?php

namespace App\Http\Controllers\Auth;

use Laracasts\Flash\Flash;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterCompleteRequest;
use App\Http\Requests\ResetCompleteRequest;
use App\Contracts\Authentication;
use App\Exceptions\InvalidOrExpiredHash;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserAlreadyExistsException;

class AuthController extends Controller
{
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function getLogin()
    {
        return view('frontend.login');
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
            Flash::success(trans('messages.successfully logged in'));

            return redirect()->intended('/');
        }

        Flash::error($error);

        return redirect()->back()->withInput();
    }

    public function getReset()
    {
        return view('frontend.reset.begin');
    }

    public function postReset(ResetRequest $request)
    {
        try {
            $this->dispatchFrom('App\Jobs\BeginResetProcess', $request);
        } catch (UserNotFoundException $e) {
            Flash::error(trans('messages.no user found'));

            return redirect()->back()->withInput();
        }

        Flash::success(trans('messages.check email to reset password'));

        return redirect()->route('reset');
    }

    public function getResetComplete()
    {
        return view('frontend.reset.complete');
    }

    public function postResetComplete($userId, $code, ResetCompleteRequest $request)
    {
        try {
            $this->dispatchFromArray(
                'App\Jobs\CompleteResetProcess',
                array_merge($request->all(), ['userId' => $userId, 'code' => $code])
            );
        } catch (UserNotFoundException $e) {
            Flash::error(trans('messages.user no longer exists'));

            return redirect()->back()->withInput();
        } catch (InvalidOrExpiredHash $e) {
            Flash::error(trans('messages.invalid reset code'));

            return redirect()->back()->withInput();
        }
        Flash::success(trans('messages.password reset'));

        return redirect()->route('login');
    }

    public function getRegister()
    {
        return view('frontend.register.begin');
    }

    public function postRegister(RegisterRequest $request)
    {
        try {
            $this->dispatchFrom('App\Jobs\BeginRegisterProcess', $request);
        } catch (UserAlreadyExistsException $e) {
            Flash::error(trans('messages.user already exists'));

            return redirect()->back()->withInput();
        }

        Flash::success(trans('messages.check email to register account'));

        return redirect()->route('register');
    }

    public function getRegisterComplete($email, $hash)
    {
        $temporary = $this->auth->checkExistsHash($email, $hash);

        if (!$temporary) {
            Flash::error(trans('messages.invalid register code'));

            return redirect()->route('register');
        }

        return view('frontend.register.complete', compact('temporary'));
    }

    public function postRegisterComplete($email, $hash, RegisterCompleteRequest $request)
    {
        try {
            $input = array_merge($request->all(), ['email' => $email, 'hash' => $hash]);
            $this->auth->register($input);
        } catch (InvalidOrExpiredHash $ex) {
            Flash::success(trans('messages.invalid register code'));

            return redirect()->route('register.complete')->withInput();
        }
        Flash::success(trans('messages.successfully register account'));

        return redirect()->route('login');
    }

    public function getLogout()
    {
        $this->auth->logout();

        return redirect()->route('login');
    }
}
