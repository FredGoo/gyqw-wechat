<?php namespace App\Http\Controllers\WechatApi;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use wechat;

class HomeController extends Controller {
  // wechat hepler
  private $wechat = null;

  public function __construct(){
    // 初始化wechat helper
    $options = array(
      'token' => env('WECHAT_TOKEN'),
      'encodingaeskey' => env('WECHAT_ENCODINGAESKEY'),
      'appid' => env('WECHAT_APPID'),
      'appsecret' => env('WECHAT_APPSECRET')
    );

    $this->wechat = new Wechat($options);
  }

  /**
   * 校验微信信息来源，并分发请求
   *
   * @return Response
   *
   */
  public function index(){
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
          'name' => '给我赞',
          'url' => $this->getWebLoginURL('http://zan.shihuang.org/api/login/apply', 'gyqw', 'snsapi_base')
        ),
        array(
          'type' => 'view',
          'name' => '批准赞',
          'url' => $this->getWebLoginURL('http://zan.shihuang.org/api/login/approve', 'gyqw', 'snsapi_base')
        ),
        array(
          'type' => 'view',
          'name' => '我的赞',
          'url' => $this->getWebLoginURL('http://zan.shihuang.org/api/login/my', 'gyqw', 'snsapi_base')
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
   * @return string
   *
   */
  private function getWebLoginURL($callback, $state, $scope){
    return $this->wechat->getOauthRedirect($callback, $state, $scope);
  }

  /**
   * wechat page login
   * @param string $redirect
   * @return void
   *
   */
  public function login($redirect){
    echo 'Loading...';
    $data = $this->wechat->getOauthAccessToken();
    // $data['openid'] = 'oT1jZsuJDKBQvaGaghckYCpPDNlo';

    // 登录成功
    if($data){
      $openID = $data['openid'];
      $userInfo = \DB::table('users')->where('wechat_open_id', $openID)->first();
      \Log::info('openID: '.$openID.', userID: '.$userInfo->id.' logged');

      \Session::put('openID', $openID);
      \Session::put('userID', $userInfo->id);

      // 跳转到相应页面
      switch($redirect){
      case 'apply':
        $url = action('\App\Http\Controllers\HomeController@applyZan');
        break;
      case 'approve':
        $url = action('\App\Http\Controllers\HomeController@approveZan');
        break;
      case 'my':
        $url = action('\App\Http\Controllers\HomeController@my');
        break;
      }
      return \Redirect::to($url);
    // 登录失败
    }else{
      \Log::error('wechat login failed, code: '.\Request::input('code'));
    }
  }

  /**
   * 发送模板信息
   *
   * @param array $data
   * @return boolean
   *
   */
  public function sendTplMsg($data){
    return $this->wechat->sendTemplateMessage($data);
  }

  /**
   * test inject data
   *
   * @return json
   *
   */
  public function testInjectSession($openID, $userID){
    \Session::put('openID', $openID);
    \Session::put('userID', $userID);

    echo json_encode(\Session::all());
  }
}
