<?php namespace App\Http\Controllers\WechatApi;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use wechat;

class HomeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
    $options = array(
      'token' => 'aaa',
      'encodingaeskey' => 'encoding',
      'appid' => 'id',
      'appsecret' => 'secret'
    );

    $wechat = new Wechat($options);
    echo 'hello';
	}
}
