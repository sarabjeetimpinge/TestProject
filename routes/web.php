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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/custom_login', 'HomeController@custom_login')->name('custom_login');
Route::get('/check_token/{token}', 'HomeController@check_token')->name('check_token');

Route::any('editEmail','HomeController@editEmail')->name('editEmail');
Route::get('dashboard','HomeController@dashboard')->name('dashboard');


//Api
