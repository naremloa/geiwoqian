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
    return 'sdfdsf';
});

/*
Auth::routes();

Route::get('/home', 'HomeController@index');
*/

Route::get('/welcome','WelcomeController@index');
Route::post('/identify/login','Identify\LoginController@postlogin');
//Route::post('/identify/register','Identify\RegisterController@')

