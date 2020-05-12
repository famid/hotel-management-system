<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth', 'namespace' => 'Web\SuperAdmin', 'prefix' => 'super-admin'], function () {
    Route::group(['middleware' => 'super.admin'], function () {
        Route::get('dashboard', 'DashboardController@dashboard')->name('superAdmin.dashboard');

        Route::get('settings', 'SettingsController@settings')->name('superAdmin.settings');
        Route::post('settings-save-process', 'SettingsController@settingsSaveProcess')->name('superAdmin.settingsSaveProcess');

        Route::get('get-all-revenue');
    });
});

