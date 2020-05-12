<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Web'], function () {
    Route::get('/', "AuthController@index")->name('home');

    Route::get('sign-in', "AuthController@signIn")->name('signIn');
    Route::post('sign-in', "AuthController@signInProcess")->name('signInProcess');
    Route::get('forget-password', "AuthController@forgetPassword")->name('forgetPassword');
    Route::post('forget-password-email-send', "AuthController@forgetPasswordEmailSendProcess")->name('forgetPasswordEmailSendProcess');
    Route::get('reset-password-code', "AuthController@forgetPasswordCode")->name('forgetPasswordCode');
    Route::post('reset-password-code', "AuthController@forgetPasswordCodeProcess")->name('forgetPasswordCodeProcess');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('sign-out', 'AuthController@signOut')->name('signOut');
    });
});
