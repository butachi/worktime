@extends('layouts.account')
@section('title')
    {{ trans('user::auth.register') }} | @parent
@stop

@section('content')
<div class="header">{{ trans('user::auth.register') }}</div>
@include('flash::message')
<form method="post" action="{{ route('register.post') }}">
    <input type="hidden" name="_token" value="{{ csrf_Token() }}"/>
    <div class="body bg-gray">
        <div class="form-group{{ $errors->has('email') ? ' has-error has-feedback' : '' }}">
            <label name="email">{{ trans('user::auth.email') }}</label>
            <input type="text" name="email" class="form-control" placeholder="{{ trans('user::auth.email') }}"/>                   
            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error has-feedback' : '' }}">
            <label name="password">{{ trans('user::auth.password') }}</label>
            <input type="password" name="password" class="form-control" placeholder="{{ trans('user::auth.password') }}"/>                        
            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error has-feedback' : '' }}">
            <label name="password_confirmation">{{ trans('user::auth.password confirmation') }}</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('user::auth.password confirmation') }}"/>                                    
            {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="footer">
            <button type="submit" class="btn btn-info btn-block">{{ trans('user::auth.register me')}}</button>
            <a href="{{ URL::route('login') }}" class="text-center">{{ trans('user::auth.I already have a membership') }}</a>
        </div>
    </div>    
</form>
@stop
