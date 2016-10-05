@extends('layouts.default')
@section('title')
{{ trans('auth.login') }} | @parent
@stop

@section('content')
<div class="login-box">
    <div class="login-logo">        
    </div><!-- /.login-logo -->
    <div class="login-form">
        <h2 class="login-box-msg">{{ trans('auth.login title') }}</h2>
        @include('flash::message')
        <form action="{{ route('login.post') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_Token() }}"/>
            <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" name="email" placeholder="{{ trans('auth.email') }}" value="{{ Input::old('email')}}">                
                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" name="password" placeholder="{{ trans('auth.password') }}">                
                {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <span>
                        <input type="checkbox" id="remember" class="checkbox" name="remember"> 
                        <label for="remember">{!! trans('auth.remember me') !!}</label>
                    </span>                    
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-default pull-right">{{ trans('auth.login') }}</button>
                </div><!-- /.col -->
            </div>
        </form>
        <a href="{{URL::route('reset')}}">{{ trans('auth.forgot password') }}</a><br>
        <p>{!! trans('auth.do not have an account') !!}<a href="{{URL::route('register')}}" class="text-center">{{ trans('auth.register now')}}</a></p>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box --> 
@stop  