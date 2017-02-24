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


Route::get('/','WelcomeController@index');
Route::post('/identify/login','Identify\LoginController@postlogin');
Route::get('/register','WelcomeController@register');
Route::post('/register/post','RegisterController@postregister');
//Route::post('/identify/register','Identify\RegisterController@')

Route::group(['middleware' => 'login'], function(){

    Route::get('/home', function(){
        return '登入成功';
    });
});
