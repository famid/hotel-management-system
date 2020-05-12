<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api', 'app.user', 'verified.user'], 'namespace' => 'Api'], function () {
    Route::get('get-all-hotels');
    Route::get('filter-hotels');
    Route::get('hotel-details');

    Route::get('get-all-rooms');
    Route::get('filter-rooms');
    Route::get('hotel-details');

    Route::get('get-user-expenditure-info');
});
