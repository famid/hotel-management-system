@extends('layouts.master')
@section('title', __('Change Password'))
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-title mb-3">{{ __('Change Password') }}</div>
                    {{ Form::open(['route' => 'passwordChangeProcess']) }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12 form-group mb-3">
                                <label for="firstName2">{{ __('Old Password') }}</label>
                                <input type="password" name="old_password" class="form-control" id="firstName2" placeholder="{{ __('Enter old password') }}">
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="lastName2">{{ __('New Password') }}</label>
                                <input type="password" name="new_password" class="form-control" id="lastName2" placeholder="{{ __('Enter new password') }}">
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="exampleInputEmail2">{{ __('Confirm Password') }}</label>
                                <input type="password" name="confirm_password" class="form-control" id="exampleInputEmail2" placeholder="{{ __('Confirm password') }}">
                            </div>

                            <div class="col-md-12">
                                <button class="btn btn-primary">{{ __('Change Password') }}</button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
