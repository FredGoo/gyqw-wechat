<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// wechat server api
Route::group(['prefix' => 'api', 'namespace' => 'WechatApi'],function(){
    Route::match(['get', 'post'], '/', 'HomeController@index');
    Route::get('login/{accessToken}', 'HomeController@login');
});

// page
Route::get('/', function(){
  return 'index';
});
