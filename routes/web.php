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
    Route::get('/identify/logout','Identify\LoginController@logout');
    Route::get('/home','HomeController@index');
    Route::get('/home/notify', 'NotifyController@index');
    Route::get('/user/notify/get-check', 'NotifyController@getCheck');
    Route::get('/user/timeline','HomeController@getTimeline');
    Route::get('/producer/{url_slug}','ProducerController@index');
    Route::get('/producer/get/timeline','ProducerController@getTimeline');
    Route::get('/producer/{url_slug}/edit-reward', 'ProducerController@getEditReward');
    Route::get('/producer/setting/edit','ProducRerSettingController@index');
    Route::post('/post/producer/add-reward', 'ProducerSettingController@postAddReward');
    Route::post('/post/contribute/edit','ContributeController@postEditContribute');
    Route::post('/apply/producer','ProducerController@postApply');
    Route::get('/postpage','PostController@index');
    Route::post('/post/postsubmit/new','PostController@postNew');
    Route::post('/post/postsubmit/modify', 'PostController@postModify');
    Route::post('/post/follow','FollowController@postFollow');
    Route::post('/post/remove-follow','FollowController@postRemoveFollow');
    Route::get('/feed/{url_slug}','FeedController@index');
    Route::post('/post/img/upload','TestController@upload');
    Route::post('/post/producer/add-post-new-tag', 'PostTagController@addNewTag');
    Route::post('/post/draft/update', 'PostController@postUpdateDraft');
    Route::get('/postpage/draft-list', 'PostController@getDraftList');
    Route::get('/postpage/posted-list', 'PostController@getPostedList');
    Route::get('/user/setting/edit', 'UserSettingController@index');
//    Route::get('/home', function(){
//        return '登入成功';
//    });
});


