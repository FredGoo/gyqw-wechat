<?php namespace App\Http\Controllers\WechatApi;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use wechat;

class HomeController extends Controller {

  /**
   * valid wechat connect
   *
   * @return Response
   */
  public function index(){
    $options = array(
      'token' => env('WECHAT_TOKEN'),
      'encodingaeskey' => env('WECHAT_ENCODINGAESKEY'),
      'appid' => env('WECHAT_APPID'),
      'appsecret' => env('WECHAT_APPSECRET') 
    );

    $wechat = new Wechat($options);
    $wechat->valid();
  }

  /**
   * wechat page login
   */
  public function login($access_token){
  }
}
