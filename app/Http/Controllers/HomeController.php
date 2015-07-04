<?php namespace App\Http\Controllers;

class HomeController extends Controller {
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index(){
		return view('home');
	}

  /**
   * test
   *
   */
  public function test(){
    // 取得微信api实例
    $api = new WechatApi\HomeController;

    $data = [
      'touser' => 'oT1jZsuJDKBQvaGaghckYCpPDNlo',
      'template_id' => '9jqPzyngkwInM073oSTZkIg47D8eSnIVuQYHzaP8vxk',
      'url' => action('\App\Http\Controllers\HomeController@my'),
      'topcolor' => '#FF0000',
      'data' => [
        'num' => [
          'value' => 100
        ],
        'date' =>[
          'value' => '2015-09-23 21:23:23'
        ],
        'content' =>[
          'value' => 'content'
        ],
        'result' =>[
          'value' => 'ok'
        ],
      ]
    ];
    $res = $api->sendTplMsg($data);

    var_dump($data);
    var_dump($res);
  }

  /**
   * 申请一个赞
   * 表单
   *
   * @return void
   *
   */
  public function applyZan(){
    return view('applyZan');
  }

  /**
   * 提交申请一个赞
   *
   * @return void
   *
   */
  public function submitApplyZan(){
    $title = 'zan'; // \Request::input('title'); 赞的标题
    $num = \Request::input('num'); // 赞的数量
    $content = \Request::input('content'); // 赞的内容
    $date = date('Y-m-d H:i:s');
    $from_user = \Session::get('userID');
    $to_user = $this->getTheOne($from_user);

    if($title && $num && $content && $date){
      // 插入数据
      $res = \DB::insert('insert into zan.`order` (title, num, content, from_user, to_user, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [$title, $num, $content, $from_user, $to_user, $date, $date]);

      \Log::info('insert into order '.var_export($res ,true));
      if($res){
        // 发送模板信息
        $toUserInfo = \DB::table('users')->where([
          'id' => $to_user,
        ])->first();
        $arr = [
          'openID' => $toUserInfo->wechat_open_id,
          'tplID' => 'Wjv0qtHVQC6pxAHxFR8I1JRukfEtiy2bDVKouv9-RMU',
          'url' => action('\App\Http\Controllers\HomeController@approveZan'),
          'num' => $num,
          'date' => $date,
          'content' => $content,
          'result' => '',
        ];

        $this->sendTpl($arr);
        echo 'success';
      }else{
        echo 'fail';
      }
    }else{
      echo 'lack of params';
    }
  }

  /**
   * 审批一个赞
   * 表单
   *
   * @return void
   *
   */
  public function approveZan(){
    $userID = \Session::get('userID');

    // 获取订单数据
    $data['orders'] = \DB::table('order')->where(array(
      'to_user' => $userID,
      'status' => 0
    ))->get();

    $base_url = action('\App\Http\Controllers\HomeController@submitApproveZan');

    return view('approveZan', ['data' => $data, 'base_url' => $base_url]);
  }

  /**
   * 提交审批一个赞
   *
   * @param int $orderID
   * @param string $status
   * @return void
   *
   */
  public function submitApproveZan($orderID, $status){
    $orderInfo = \DB::table('order')->where([
      'id' => $orderID,
      'status' => 0
    ])->first();

    if($orderInfo && isset($status)){
      // 现在时间
      $date = date('Y-m-d H:i:s');
      $fromUserInfo = \DB::table('users')->where([
      'id' => $orderInfo->from_user,
    ])->first();
;

      switch($status){
      // rejected
      case 'reject':
        \DB::table('order')->where([
          'id' => $orderInfo->id,
          'status' => 0
        ])->update(['status' => 400, 'updated_at' => $date]);

        // tpl的result文字信息
        $resStr = '没有给哦';
        break;

      // approved
      case 'ok':
        \DB::transaction(function()use($orderInfo, $date){
          \DB::table('order')->where([
            'id' => $orderInfo->id,
            'status' => 0
          ])->update(['status' => 200, 'updated_at' => $date]);
          \DB::table('users')->where('id', $orderInfo->from_user)->increment('num', $orderInfo->num);

          // log
          \Log::info('add user '.$orderInfo->from_user.', '.$orderInfo->num.' zans');
        });

        // tpl的result文字信息
        $resStr = '得到啦';
        break;
      }

      // 发送模板信息
      $arr = [
        'openID' => $fromUserInfo->wechat_open_id,
        'tplID' => '9jqPzyngkwInM073oSTZkIg47D8eSnIVuQYHzaP8vxk',
        'url' => action('\App\Http\Controllers\HomeController@my'),
        'num' => $orderInfo->num,
        'date' => $date,
        'content' => $orderInfo->content,
        'result' => $resStr
      ];

      $this->sendTpl($arr);
    }

    $url = action('\App\Http\Controllers\HomeController@approveZan');
    return \Redirect::to($url);
  }

  /**
   * 个人中心
   *
   * @return void
   *
   */
  public function my(){
    $userID = \Session::get('userID');

    // 获取订单数据
    $orders = \DB::table('order')->where(array(
      'from_user' => $userID,
      'status' => 200,
    ))->get();

    // 获取个人信息
    $profile = \DB::table('users')->where(array(
      'id' => $userID,
    ))->first();

    return view('my', ['profile' => $profile, 'orders' => $orders]);
  }

  /**
   * get the one
   *
   * @param $from_user
   * @return int
   *
   */
   public function getTheOne($from){
     $res = \DB::table('users')->where('id', $from)->pluck('the_one');

     return $res;
   }

  /**
   * 发送模板信息
   *
   * @param array $arr
   * @return boolean
   *
   */
  public function sendTpl($arr){
    // 取得微信api实例
    $api = new WechatApi\HomeController;

    // 构造tpl数据
    $data = [
      'touser' => $arr['openID'],
      'template_id' => $arr['tplID'],
      'url' => $arr['url'],
      'topcolor' => '#FF0000',
      'data' => [
        'num' => [
          'value' => $arr['num']
        ],
        'date' =>[
          'value' => $arr['date']
        ],
        'content' =>[
          'value' => $arr['content']
        ],
        'result' =>[
          'value' => $arr['result']
        ],
      ]
    ];

    return $api->sendTplMsg($data);
  }
}
