@extends('layouts.default')

@section('title')
{{ trans('auth.reset password') }} | @parent
@stop

@section('content')
<div class="login-form">
    <div class="register-box-body">
        <h2 class="login-box-msg">{{ trans('auth.reset password') }}</h2>
        @include('flash::message')
        <form action="{{ route('reset.post') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_Token() }}" >
            <div class="form-group{{ $errors->has('email') ? ' has-error has-feedback' : '' }}">
                <input type="email" name="email" placeholder="{{ trans('auth.email') }}" value="{{ Input::old('email')}}" />
                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
            </div>            
            <div class="row">
                <div class="col-xs-8">
                    <div>
                        <a href="{{URL::route('login')}}" class="text-center">{{ trans('auth.I remembered my password') }}</a>
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-default pull-right">{{ trans('auth.reset password') }}</button>                    
                </div><!-- /.col -->
            </div>
        </form>        
    </div>
</div>
@stop