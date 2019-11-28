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
Route::middleware('auth:api')->put('/user', 'UserController@update');
Route::middleware('auth:api')->delete('/user', 'UserController@destroy');

Route::middleware('auth:api')->prefix('/projects')->group(function () {
    Route::get('/all', 'ProjectController@indexWithModels');
    Route::get('/{id}/all', 'ProjectController@showWithModels');

    Route::get('/', 'ProjectController@index');
    Route::get('/{id}', 'ProjectController@show');
    Route::post('/', 'ProjectController@store');
    Route::put('/{id}', 'ProjectController@update');
    Route::delete('/{id}', 'ProjectController@destroy');

    Route::get('/{project_id}/tasks', 'TaskController@index');
    Route::get('/{project_id}/tasks/{id}', 'TaskController@show');
    Route::post('/{project_id}/tasks', 'TaskController@store');
    Route::put('/{project_id}/tasks/{id}', 'TaskController@update');
    Route::put('/{project_id}/tasks/{id}/toggleCheck', 'TaskController@checkToggle');
    Route::delete('/{project_id}/tasks/{id}', 'TaskController@destroy');

});
