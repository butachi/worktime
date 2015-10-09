@extends('layouts.account')

@section('title')
    {{ trans('user::auth.reset password') }} | @parent
@stop

@section('content')
<div class="header">{{ trans('user::auth.reset password') }}</div>
@include('flash::message')
<form method="post" action="{{ route('reset.post') }}">
    <input type="hidden" name="_token" value="{{ csrf_Token() }}"
    <div class="body bg-gray">
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <input type="email" name="email" class="form-control"
                   placeholder="{{ trans('user::auth.email') }}" value="{{ Input::old('email')}}" required=""/>
            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
        </div>
        <button type="submit" class="btn btn-info btn-block">{{ trans('user::auth.reset password') }}</button>
        <p><a href="{{URL::route('login')}}">{{ trans('user::auth.I remembered my password') }}</a></p>
    </div>    
    <div class="footer">        
    </div>
</form>

@stop
