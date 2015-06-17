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
    Route::any('login', 'HomeController@login');
});

// page
Route::get('/', function(){
  return 'index';
});
// 申请赞
Route::get('apply-zan', 'HomeController@applyZan');
// 提交申请赞
Route::post('submit-apply-zan', 'HomeController@submitApplyZan');
