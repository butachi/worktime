@extends('layouts.default')

@section('title')
    {{ trans('auth.reset password') }} | @parent
@stop

@section('content')
<div class="login-form">
    <div class="register-box-body">
        <h2>{{ trans('auth.reset password') }}</h2>
        @include('flash::message')
        <form action="" method="post">
            <input type="hidden" name="_token" value="{{ csrf_Token() }}" >
            <div class="body bg-gray">
                <div class="form-group{{ $errors->has('password') ? ' has-error has-feedback' : '' }}">
                    <label name='paswword'>{{ trans('auth.password') }}</label>
                    <input type="password" name="password" placeholder="{{trans('auth.password')}}"/>            
                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error has-feedback' : '' }}">
                    <label name='password_confirmation'>{{ trans('auth.password confirmation') }}</label>
                    <input type="password" name="password_confirmation" placeholder="{{ trans('auth.password confirmation') }}">                        
                    {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="footer">
                <button type="submit" class="btn btn-info btn-block">{{ trans('auth.reset password') }}</button>
            </div>
        </form>
    </div>
</div>
@stop
