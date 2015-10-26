@extends('layouts.backend')
@section('title')
{{ trans('user::auth.login') }} | @parent
@stop

@section('content')
<div class="login-box">
    <div class="login-logo">        
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('user::auth.login') }}</p>
        <form action="{{ route('login.post') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_Token() }}"/>
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" placeholder="{{ trans('user::auth.email') }}" value="{{ Input::old('email')}}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label><input type="checkbox"> {{ trans('user::auth.remember me') }}</label>                  
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('user::auth.login') }}</button>
                </div><!-- /.col -->
            </div>
        </form>
        <a href="{{URL::route('reset')}}">{{ trans('user::auth.forgot password') }}</a><br>
        <a href="{{URL::route('register')}}" class="text-center">{{ trans('user::auth.register')}}</a>

    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->        
@stop  