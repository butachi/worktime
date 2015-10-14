@extends('layouts.account')

@section('title')
    {{ trans('user::auth.register') }}
@stop

@section('content')
<div class="register-box">    
    <div class="register-box-body">
        <p class="login-box-msg">{{ trans('user::auth.register') }}</p>
        <form action="{{ route('register.post') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_Token() }}" >
            <div class="form-group has-feedback">
                <input type="text" name="full_name" class="form-control" placeholder="{{ trans('user::auth.fullname') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" placeholder="{{ trans('user::auth.email') }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="{{ trans('user::auth.password') }}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('user::auth.password confirmation') }}">
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label><input type="checkbox"> I agree to the <a href="#">terms</a></label>                  
                    </div>                    
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('user::auth.register me') }}</button>
                </div><!-- /.col -->
            </div>
        </form>
        <a href="{{ URL::route('login') }}" class="text-center">{{ trans('user::auth.I already have a membership') }}</a>
    </div><!-- /.form-box -->
</div>
@stop