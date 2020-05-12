<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


require base_path('routes/web/authentication.php');
require base_path('routes/web/profile.php');
require base_path('routes/web/super_admin.php');

//Routes are inside the web directory
