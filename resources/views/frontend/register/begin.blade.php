@extends('layouts.default')

@section('title')
    {{ trans('auth.register') }}
@stop

@section('content')
<div class="login-form">    
    <div class="register-box-body">
        <h2 class="login-box-msg">{{ trans('auth.register title') }}</h2>
        @include('flash::message')
        <form action="{{ route('register.post') }}" method="post">            
            <input type="hidden" name="_token" value="{{ csrf_Token() }}" >            
            <div class="form-group{{ $errors->has('email') ? ' has-error has-feedback' : '' }}">
                <input type="email" name="email" value="{{Input::old('email')}}" placeholder="{{ trans('auth.email') }}">                
                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
            </div>             
            <div class="row">
                <div class="col-xs-8">
                    <span class="{{ $errors->has('tearms') ? ' has-error has-feedback' : '' }}">
                        <label><input type="checkbox" name="tearms"> I agree to the <a href="#">terms</a></label>
                        {!! $errors->first('tearms', '<span class="help-block">:message</span>') !!}
                    </span>                    
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-default pull-right">{{ trans('auth.register me') }}</button>                    
                </div><!-- /.col -->
            </div>
        </form>
        <a href="{{ URL::route('login') }}" class="text-center">{{ trans('auth.I already have a membership') }}</a>
    </div><!-- /.form-box -->
</div>
@stop