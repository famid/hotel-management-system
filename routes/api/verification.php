<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['language'], 'namespace' => 'Api'], function () {
    Route::group(['middleware' => ['auth:api', 'app.user']], function () {
        Route::get('resend-email-verification-code', 'AuthController@resendEmailVerificationCode')->name('api.resendEmailVerificationCode');
        Route::post('email-verification', 'AuthController@emailVerify')->name('api.emailVerification');
        Route::group(['middleware' => 'verified.user'], function () {
            Route::get('send-phone-verification-code', 'ProfileController@sendPhoneVerificationCode')->name('api.sendPhoneVerificationCode');
            Route::post('phone-verification', 'ProfileController@phoneVerify')->name('api.phoneVerification');
        });
    });
});
