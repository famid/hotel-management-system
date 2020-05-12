<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth', 'namespace' => 'Web'], function () {
    Route::get('password-change', 'ProfileController@passwordChange')->name('passwordChange');
    Route::post('password-change-process', 'ProfileController@passwordChangeProcess')->name('passwordChangeProcess');
});
