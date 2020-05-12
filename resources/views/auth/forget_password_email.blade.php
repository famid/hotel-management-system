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
                                <img src="{{ asset('assets/images/laravelLogo.png')}}" alt="" style="max-width: 50%;">
                            </div>
                            <h1 class="mb-3 text-18">{{__('Reset Password')}}</h1>
                            {{ Form::open(['route' => 'forgetPasswordEmailSendProcess']) }}
                                <div class="form-group">
                                    <label for="email">{{__('Email/Username')}}</label>
                                    <input name="email" id="email" class="form-control form-control-rounded" type="text" value="{{ old('email') }}">
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
