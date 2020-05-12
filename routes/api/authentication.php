<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['language'], 'namespace' => 'Api'], function () {
    Route::post('sign-up', "AuthController@signUp")->name('api.SignUp');
    Route::post('sign-in', "AuthController@signIn")->name('api.signIn');
    Route::post('send-forget-password-email', "AuthController@sendForgetPasswordEmail")->name('api.sendForgetPasswordEmail');
    Route::post('reset-password', "AuthController@resetPassword")->name('api.resetPassword');
    Route::post('social-login', "AuthController@socialLogin")->name('api.socialLogin');

    Route::group(['middleware' => ['auth:api', 'app.user']], function () {
        Route::post('logout', 'AuthController@logout')->name('api.logout');
    });
});
