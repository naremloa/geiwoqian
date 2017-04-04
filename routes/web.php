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
//Route::post('/register/post','RegisterController@postregister');
//Route::post('/identify/register','Identify\RegisterController@')

Route::group(['middleware' => 'login'], function(){
    Route::get('/identify/logout','Identify\LoginController@logout');
    Route::get('/home','HomeController@index');
    Route::get('/producer','ProducerController@index');
    Route::post('/apply/producer','ProducerController@postApply');
    Route::get('/postpage','PostController@index');
    Route::post('/post/submit/new','PostController@postNew');
    Route::post('/post/follow','FollowController@postFollow');
    Route::get('/feed/{url_slug}','FeedController@index');
//    Route::get('/home', function(){
//        return '登入成功';
//    });
});


