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

Route::middleware('auth:api')->get('/user', function (Request $request) {
     
    return $request->user();
});

Route::post('/validate_user', 'Api\UserController@validate_user')->name('validate_user');
Route::get('/verify_token/{token}', 'Api\UserController@verify_token')->name('verify_token');


