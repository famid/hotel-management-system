<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['language'], 'namespace' => 'Api'], function () {
    Route::group(['middleware' => ['auth:api', 'app.user']], function () {
        Route::group(['middleware' => 'verified.user'], function () {
            Route::get('get-user-profile', 'ProfileController@getUserProfile')->name('api.getUserProfile');
            Route::post('update-user-profile', 'ProfileController@updateUserProfile')->name('api.updateUserProfile');
            Route::post('update-password', 'ProfileController@updatePassword')->name('api.updatePassword');

            Route::get('language-list', 'ProfileController@languageList')->name('api.languageList');
            Route::post('set-language', 'ProfileController@setLanguage')->name('api.setLanguage');
        });
    });
});
