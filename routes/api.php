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

Route::middleware('auth:api')->get('/user', 'UserController@show');
Route::middleware('auth:api')->get('/user/{id}/tasks', 'TaskController@indexWithModels');
Route::middleware('auth:api')->put('/user', 'UserController@update');
Route::middleware('auth:api')->delete('/user', 'UserController@destroy');

Route::middleware('auth:api')->post('/user/settings', 'UserController@settingsUpdateOrDestroy');
Route::middleware('auth:api')->put('/user/settings', 'UserController@settingsUpdateOrDestroy');

Route::middleware('auth:api')->prefix('/projects')->group(function () {
    Route::get('/all', 'ProjectController@indexWithModels');
    Route::get('/{id}/all', 'ProjectController@showWithModels');

    Route::get('/', 'ProjectController@index');
    Route::get('/{id}', 'ProjectController@show');
    Route::post('/', 'ProjectController@store');
    Route::put('/{id}', 'ProjectController@update');
    Route::delete('/{id}', 'ProjectController@destroy');
});

Route::middleware('auth:api')->get('/tasks', 'TaskController@index');
Route::middleware('auth:api')->get('/tasks/{id}', 'TaskController@show');
Route::middleware('auth:api')->post('/tasks', 'TaskController@store');
Route::middleware('auth:api')->put('/tasks/{id}', 'TaskController@update');
Route::middleware('auth:api')->post('/tasks/{id}/check', 'TaskController@check');
Route::middleware('auth:api')->delete('/tasks/{id}', 'TaskController@destroy');
