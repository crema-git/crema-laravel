<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'user'], function(){
   Route::post('register', 'AuthController@register');
   Route::post('login', 'AuthController@login');
   Route::get('logout', 'AuthController@logout');
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('refresh/jwt/token', 'AuthController@refreshToken');
    Route::get('user/profile', 'UserController@profile');
});

