@extends('layouts.default')

@section('title')
    {{ trans('auth.register') }}
@stop

@section('content')
<div class="login-form">    
    <div class="register-box-body">
        <h2 class="login-box-msg">{{ trans('auth.register') }}</h2>
        @include('flash::message')
        <form action="{{ route('register.complete.post', [$temporary->email, $temporary->hash]) }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_Token() }}" >
            <div class="row"> 
                <div class="col-xs-6">
                    <div class="form-group{{ $errors->has('name_family') ? ' has-error has-feedback' : '' }}">
                        <input type="text" value="{{Input::old('name_family')}}" name="name_family" placeholder="{{ trans('auth.name family') }}">
                        {!! $errors->first('name_family', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group{{ $errors->has('name_fore') ? ' has-error has-feedback' : '' }}">
                        <input type="text" value="{{Input::old('name_fore')}}" name="name_fore" placeholder="{{ trans('auth.name fore') }}">
                        {!! $errors->first('name_fore', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="col-xs-6">
                    <div class="form-group{{ $errors->has('name_family_eng') ? ' has-error has-feedback' : '' }}">
                        <input type="text" value="{{Input::old('name_family_eng')}}" name="name_family_eng" placeholder="{{ trans('auth.name family english') }}">
                        {!! $errors->first('name_family_eng', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group{{ $errors->has('name_family_fore') ? ' has-error has-feedback' : '' }}">
                        <input type="text" value="{{Input::old('name_family_fore')}}" name="name_family_fore" placeholder="{{ trans('auth.name family fore') }}">
                        {!! $errors->first('name_family_fore', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
            <div class="form-group{{ $errors->has('email') ? ' has-error has-feedback' : '' }}">
                <input type="email" name="email" value="{{Input::old('email', $temporary->email)}}" placeholder="{{ trans('auth.email') }}">                
                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error has-feedback' : '' }}">
                <input type="password" value="{{Input::old('password')}}" name="password" placeholder="{{ trans('auth.password') }}">                
                {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error has-feedback' : '' }}">
                <input type="password" name="password_confirmation" placeholder="{{ trans('auth.password confirmation') }}">                
                {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="row"> 
                <div class="col-xs-6">                    
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="form-group{{ $errors->has('day') ? ' has-error has-feedback' : '' }}">
                                <input type="text" value="{{Input::old('day')}}" name="day" placeholder="{{ trans('auth.day') }}">                                                        
                            </div>
                        </div>
                        <div class="col-xs-5">
                            <select name="month" >
                                <?php
                                for ($i = 1; $i <= 12; $i++)
                                {?>
                                    @if (Input::old('month') == $i)
                                        <option value="{{ $i }}" selected>{!!trans('auth.month')!!} {!! $i !!}</option>
                                    @else
                                        <option value="{{$i}}"> {!!trans('auth.month')!!} {!! $i !!}</option>
                                    @endif
                                <?php
                                }
                                ?>
                            </select>
                            {!! $errors->first('month', '<span class="help-block">:message</span>') !!}
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group{{ $errors->has('year') ? ' has-error has-feedback' : '' }}">
                                <input type="text" value="{{Input::old('year')}}" name="year" placeholder="{{ trans('auth.year') }}">
                            </div>
                        </div>
                    </div>                    
                    <div class="{{$errors->has('birth') ? ' has-error has-feedback' : '' }}">
                        <span class="help-block">
                            @if($errors->has('day') || $errors->has('year'))
                            {!! $errors->first('day', ':message') !!}
                            {!! $errors->first('year', ':message') !!}
                            @else
                            {!! $errors->first('birth', ':message') !!}
                            @endif                            
                        </span>                        
                    </div>
                    
                </div>
                <div class="col-xs-6">
                    <div class="form-group{{ $errors->has('sex') ? ' has-error has-feedback' : '' }}">
                        <select name="sex">
                            <option value="male">{!! trans('auth.male') !!}</option>
                            <option value="female">{!! trans('auth.female') !!}</option>
                        </select>                        
                        {!! $errors->first('sex', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
            <div class="form-group{{ $errors->has('passport_no') ? ' has-error has-feedback' : '' }}">
                <input type="text" name="passport_no" placeholder="{{ trans('auth.passport number') }}">                
                {!! $errors->first('passport_no', '<span class="help-block">:message</span>') !!}
            </div>            
            <div class="row">
                <div class="col-xs-12">                                    
                    <button type="submit" class="btn btn-primary pull-right">{{ trans('auth.register me') }}</button>
                </div><!-- /.col -->
            </div>
        </form>        
    </div><!-- /.form-box -->
</div>
@stop