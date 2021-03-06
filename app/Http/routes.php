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
    Route::any('/', 'HomeController@index');
    Route::any('login/{redirect}', 'HomeController@login');
    // test inject
    Route::get('test-inject-session/{openID}/{userID}', 'HomeController@testInjectSession');
});

// page
Route::get('/', function(){
  return 'index';
});
// test
Route::get('test', 'HomeController@test');
// 申请赞
Route::get('apply-zan', 'HomeController@applyZan');
// 提交申请赞
Route::post('submit-apply-zan', 'HomeController@submitApplyZan');
// 审批赞
Route::get('approve-zan', 'HomeController@approveZan');
// 提交审批赞
Route::get('submit-approve-zan/{orderID?}/{status?}', 'HomeController@submitApproveZan');
// 个人中心
Route::get('my/{channel?}', 'HomeController@my');
