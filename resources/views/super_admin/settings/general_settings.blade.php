@extends('layouts.master')
@section('title', __('General Settings'))
@section('style')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/dropify.min.css')}}">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-title mb-3">{{ __('Settings') }}</div>
                    {{ Form::open(['route' => 'superAdmin.settingsSaveProcess', 'files' => true ]) }}
                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label for="twilio_sid">{{ __('Twilio SID') }}</label>
                                <input type="text" name="{{'twilio_sid'}}" class="form-control" id="twilio_sid" value="{{ (isset($settings) && isset($settings['twilio_sid'])) ? $settings['twilio_sid'] : '' }}">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="twilio_token">{{ __('Twilio Token') }}</label>
                                <input type="text" name="{{'twilio_token'}}" class="form-control" id="twilio_token" value="{{ (isset($settings) && isset($settings['twilio_token'])) ? $settings['twilio_token'] : '' }}">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="twilio_from">{{ __('From Number') }}</label>
                                <input type="text" name="{{'twilio_from'}}" class="form-control" id="twilio_from" value="{{ (isset($settings) && isset($settings['twilio_from'])) ? $settings['twilio_from'] : '' }}">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="push_notification_cost">{{ __('Push Notification Cost') }}</label>
                                <input type="number" min="0" name="{{'push_notification_cost'}}" class="form-control" id="push_notification_cost" value="{{ (isset($settings) && isset($settings['push_notification_cost'])) ? $settings['push_notification_cost'] : '' }}">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="email_notification_cost">{{ __('Email Notification Cost') }}</label>
                                <input type="number" min="0" name="{{'email_notification_cost'}}" class="form-control" id="email_notification_cost" value="{{ (isset($settings) && isset($settings['email_notification_cost'])) ? $settings['email_notification_cost'] : '' }}">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="sms_notification_cost">{{ __('Sms Notification Cost') }}</label>
                                <input type="number" min="0" name="{{'sms_notification_cost'}}" class="form-control" id="sms_notification_cost" value="{{ (isset($settings) && isset($settings['sms_notification_cost'])) ? $settings['sms_notification_cost'] : '' }}">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <div class="form-group">
                                    <div class="controls">
                                        <label class="form-label">{{__('Default Currency')}}</label>
                                        <select class="form-control" name="default_currency">
                                            @foreach(currencySymbol() as $key => $value)
                                                <option value="{{$key}}"
                                                        @if((isset($settings) && isset($settings['default_currency']) && $settings['default_currency'] == $key) || ($key == old('default_currency'))) selected  @elseif(!isset($settings['default_currency']) && ($key != old('default_currency')) && $key == 'EUR') selected @endif>{{$key}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <pre class="text-danger">{{$errors->first('default_currency')}}</pre>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="logo">{{__('Upload Logo')}}</label>
                                <input type="file" name="main_logo" id="input-file-now" class="dropify" data-default-file="{{ isset($settings['main_logo']) ? asset(logoViewPath() .$settings['main_logo']) : '' }}" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="logo">{{__('Upload Image')}}</label>
                                <input type="file" name="main_image" id="input-file-now" class="dropify" data-default-file="{{ isset($settings['main_image']) ? asset(imageViewPath() . $settings['main_image']) : '' }}" />
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type='text/javascript' src='{{ asset('assets/js/dropify.min.js') }}'></script>
    <script>
        $(document).ready(function () {
            $('.dropify').dropify();
        });
    </script>
@endsection

