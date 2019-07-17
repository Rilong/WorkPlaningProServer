<?php

use Illuminate\Http\Request;

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

Route::post('/login', 'AuthController@login')->name('apiLogin');
Route::post('/register', 'AuthController@register')->name('apiRegister');
Route::middleware('auth:api')->post('/logout', 'AuthController@logout')->name('apiLogout');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('user', 'UserController@show');
Route::middleware('auth:api')->put('user', 'UserController@update');
Route::middleware('auth:api')->delete('user', 'UserController@destroy');