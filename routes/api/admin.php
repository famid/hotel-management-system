<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api', 'admin', 'verified.user'], 'namespace' => 'Api'], function () {
    Route::post('create-hotel');
    Route::post('update-hotel');
    Route::post('delete-hotel');
    Route::post('create-hotel-details');
    Route::post('update-hotel-details');
    Route::post('create-hotel-features');
    Route::post('update-hotel-features');
    Route::post('delete-hotel-features');
    Route::get('get-all-hotels');
    Route::get('filter-hotels');
    Route::get('hotel-details');

    Route::post('create-room');
    Route::post('update-room');
    Route::post('delete-room');
    Route::get('get-all-rooms');
    Route::get('filter-rooms');
    Route::get('hotel-details');

    Route::get('get-user-revenue-info');
});
