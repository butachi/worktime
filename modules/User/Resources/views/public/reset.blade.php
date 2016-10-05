@extends('layouts.account')

@section('title')
{{ trans('user::auth.reset password') }} | @parent
@stop

@section('content')
<div class="register-box">
    <div class="register-box-body">
        <p class="login-box-msg">{{ trans('user::auth.reset password') }}</p>
        <form action="{{ route('reset.post') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_Token() }}" >
            <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="{{ trans('user::auth.email') }}" value="{{ Input::old('email')}}" />
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>            
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <a href="{{URL::route('login')}}" class="text-center">{{ trans('user::auth.I remembered my password') }}</a>
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('user::auth.reset password') }}</button>
                </div><!-- /.col -->
            </div>
        </form>        
    </div>
</div>
@stop