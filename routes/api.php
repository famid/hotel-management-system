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


require base_path('routes/api/authentication.php');
require base_path('routes/api/verification.php');
require base_path('routes/api/profile.php');
require base_path('routes/api/admin.php');
require base_path('routes/api/user.php');

//Routes are inside the api directory
