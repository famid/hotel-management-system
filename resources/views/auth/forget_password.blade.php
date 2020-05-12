@extends('auth.layouts.auth')
@section('title', __('Forget Password'))
@section('content')
    <div class="auth-layout-wrap" style="background-image: url({{ empty(allSetting('main_image')) ? asset('assets/images/background.jpg') : asset(imageViewPath() . allSetting('main_image')) }}); height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover; ">
        <div class="auth-content">
            <div class="card o-hidden">
                <div class="row">
                    <div class="offset-2 col-md-8">
                        <div class="p-4">
                            <div class="text-center mb-4">
                                <img src="{{ empty(allSetting('main_logo')) ? asset('assets/images/laravelLogo.png') : asset(logoViewPath() . allSetting('main_logo'))}}" alt="" style="max-width: 50%;">
                            </div>
                            <h1 class="mb-3 text-18">{{__('Reset Password')}}</h1>
                            {{ Form::open(['route' => 'forgetPasswordCodeProcess']) }}
                                <div class="form-group">
                                    <label for="reset_password_code">{{__('Reset Password Code')}}</label>
                                    <input name="reset_password_code" id="reset_password_code" class="form-control form-control-rounded" type="text" value="{{ old('reset_password_code') }}">
                                </div>
                                <div class="form-group">
                                    <label for="password">{{__('New Password')}}</label>
                                    <input name="new_password" id="password" class="form-control form-control-rounded" type="password">
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">{{__('Confirm Password')}}</label>
                                    <input name="confirm_password" id="confirm_password" class="form-control form-control-rounded" type="password">
                                </div>
                                <button class="btn btn-rounded btn-primary btn-block mt-2">{{__('Submit')}}</button>
                            {{ Form::close() }}
                            <div class="mt-3 text-center">
                                <a href="{{ route('signIn') }}" class="text-muted"><u>{{__('Sign In')}}</u></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
