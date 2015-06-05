<?php namespace App\Http\Controllers\WechatApi;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use wechat;

class HomeController extends Controller {
  // wechat hepler
  private $wechat = null;

  /**
   * 校验微信信息来源，并分发请求
   *
   * @return Response
   *
   */
  public function index(){
    // 微信初始化
    $options = array(
      'token' => env('WECHAT_TOKEN'),
      'encodingaeskey' => env('WECHAT_ENCODINGAESKEY'),
      'appid' => env('WECHAT_APPID'),
      'appsecret' => env('WECHAT_APPSECRET')
    );

    $this->wechat = new Wechat($options);
    // 验证信息是从微信服务器发来的
    $this->wechat->valid();

    // 回复微信信息
    $msgType = $this->wechat->getRev()->getRevType();
    switch($msgType){
    case Wechat::MSGTYPE_TEXT:
      $this->responseOnText($this->wechat->getRevData());
      break;
    }
  }

  /**
   * 回复微信文字信息
   *
   * @param array $data
   *
   * @return void
   *
   */
  public function responseOnText($data){
    // 记录传来的数据
    \Log::info('wechat request data ### '.implode(' --- ', $data));

    // 获取请求的字符串内容
    $reqContentStr = strtolower(trim($data['Content']));

    switch($reqContentStr){
    // 设置菜单
    case 'menu':
      $this->wechat->createMenu($this->getMenuData());

      $resStr = 'menu';
      break;
    default:
      $resStr = '爱你宝贝';
      break;
    }

    // 返回微信信息
    $res = $this->wechat->text($resStr)->reply('', true);
    \Log::info('wechat response xml '.$res);
    echo $res;
  }

  /**
   * 微信菜单数据
   *
   * @return array
   *
   */
  private function getMenuData(){
    $menu = array(
      'button' => [
        array(
          'type' => 'view',
          'name' => '赞?!',
          'url' => $this->getWebLoginURL('http://zan.shihuang.org/api/login', 'gyqw', 'snsapi_base')
        )
      ]
    );

    return $menu;
  }

  /**
   * 获取微信网页登录链接
   *
   * @param string $callback
   * @param string $state
   * @param string $scope
   *
   * @return string
   *
   */
  private function getWebLoginURL($callback, $state, $scope){
    return $this->wechat->getOauthRedirect($callback, $state, $scope);
  }

  /**
   * wechat page login
   *
   */
  public function login($accessToken){
    return $accessToken;
  }
}
