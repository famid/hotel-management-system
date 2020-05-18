<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['language'], 'namespace' => 'Api'], function () {
    /*
     * ---------------------------------------------------------------------------------------------------------
     * AUTHENTICATION API
     * ---------------------------------------------------------------------------------------------------------
     * */
    Route::group(['namespace' => 'Auth'] , function () {
        Route::post('sign-up', "AuthController@signUp")->name('api.SignUp');
        Route::post('sign-in', "AuthController@signIn")->name('api.signIn');
        Route::post('send-forget-password-email', "AuthController@sendForgetPasswordEmail")->name('api.sendForgetPasswordEmail');
        Route::post('reset-password', "AuthController@resetPassword")->name('api.resetPassword');
        Route::post('social-login', "AuthController@socialLogin")->name('api.socialLogin');
    });
    /*
     * ---------------------------------------------------------------------------------------------------------
     * AUTHENTICATED API
     * ---------------------------------------------------------------------------------------------------------
     * */
    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['namespace' => 'Auth'] , function () {
            Route::post('logout', 'AuthController@logout')->name('api.logout');
            Route::get('resend-email-verification-code', 'AuthController@resendEmailVerificationCode')->name('api.resendEmailVerificationCode');
            Route::post('email-verification', 'AuthController@emailVerify')->name('api.emailVerification');
        });
        /*
         * ---------------------------------------------------------------------------------------------------------
         * VERIFIED API
         * ---------------------------------------------------------------------------------------------------------
         * */
        Route::group(['middleware' => 'verified.user'], function () {

            Route::group(['namespace' => 'Profile'] , function () {
                Route::get('get-user-profile', 'ProfileController@getUserProfile')->name('api.getUserProfile');
                Route::post('update-user-profile', 'ProfileController@updateUserProfile')->name('api.updateUserProfile');
                Route::post('update-password', 'ProfileController@updatePassword')->name('api.updatePassword');

                Route::get('language-list', 'ProfileController@languageList')->name('api.languageList');
                Route::post('set-language', 'ProfileController@setLanguage')->name('api.setLanguage');

                Route::get('send-phone-verification-code', 'ProfileController@sendPhoneVerificationCode')->name('api.sendPhoneVerificationCode');
                Route::post('phone-verification', 'ProfileController@phoneVerify')->name('api.phoneVerification');
            });
            /*
             * ---------------------------------------------------------------------------------------------------------
             * ADMIN && USER COMMON API
             * ---------------------------------------------------------------------------------------------------------
             * */
            Route::group(['namespace' => 'Hotel'] , function () {
                Route::get('get-all-hotel', 'HotelController@getAllHotel')->name('getAllHotel');
               // Route::get('filter-hotels', 'HotelController@filterHotel')->name('filterHotel');
                Route::post('hotel-details', 'HotelController@hotelDetails')->name('hotelDetails');

            });
            Route::group(['namespace' => 'Room'] , function () {
                Route::get('get-all-room', 'RoomController@getAllRoom')->name('getAllRoom') ;
                //Route::get('filter-rooms', 'RoomController@filterRooms')->name('filterRooms');
                Route::post('room-details', 'RoomController@roomDetails')->name('roomDetails');
            });

            Route::group(['middleware' => 'admin'] , function (){

                Route::group(['namespace' => 'Hotel'] , function () {
                    Route::post('create-hotel', 'HotelController@createHotel')->name('createHotel');
                    Route::post('update-hotel', 'HotelController@updateHotel')->name('updateHotel');
                    Route::post('delete-hotel', 'HotelController@deleteHotel')->name('deleteHotel');
                    Route::post('create-hotel-details', 'HotelDetailController@createDetails')->name('createDetails');
                    Route::post('update-hotel-details', 'HotelDetailController@updateDetails')->name('updateDetails');
                    Route::post('create-hotel-features', 'HotelFeatureController@createFeature')->name('createFeature');
                    Route::post('update-hotel-features', 'HotelFeatureController@updateFeature')->name('updateFeature');
                    Route::post('delete-hotel-features', 'HotelFeatureController@deleteFeature')->name('deleteFeature');
                });

                Route::group(['namespace' => 'Room'] , function () {
                    Route::post('create-room', 'RoomController@createRoom')->name('createRoom');
                    Route::post('update-room', 'RoomController@updateRoom')->name('updateRoom');
                    Route::post('delete-room', 'RoomController@deleteRoom')->name('deleteRoom');
                    Route::post('delete-room-image', 'RoomController@deleteRoomImage')->name('deleteRoomImage');
                    Route::post('update-room-images', 'RoomController@updateRoomImages')->name('updateRoomImages');
                });

                Route::group(['namespace' => 'Revenue'] , function () {
                    Route::get('get-user-revenue-info', 'RevenueController@getUserRevenueInfo')->name('getUserRevenueInfo');
                });

                Route::group(['namespace' => 'Booking'] , function () {
                    Route::post('check-in-room', 'RoomBookingController@checkInRoom')->name('checkInRoom');
                });

                Route::group(['namespace' => 'History'] , function () {
                    Route::get('get-expenditure-info', 'HistoryController@getExpenditureInfo')->name('getExpenditureInfo');
                });

            });

        });
    });
});

