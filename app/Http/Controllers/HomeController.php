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
      'template_id' => 'QyTb7PLhkm9tG2HRMHAtB2UluGE0fwCEuGnL8uyiS3c',
      'url' => action('\App\Http\Controllers\HomeController@my'),
      'tpcolor' => '#FF0000',
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
      switch($status){
      // rejected
      case 'reject':
        \DB::table('order')->where([
          'id' => $orderInfo->id,
          'status' => 0
        ])->update(['status' => 400]);
        break;

      // approved
      case 'ok':
        \DB::transaction(function()use($orderInfo){
          \DB::table('order')->where([
            'id' => $orderInfo->id,
            'status' => 0
          ])->update(['status' => 200]);
          \DB::table('users')->where('id', $orderInfo->from_user)->increment('num', $orderInfo->num);

          // log
          \Log::info('add user '.$orderInfo->from_user.', '.$orderInfo->num.' zans');
        });
        break;
      }

      // 发送模板信息
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
}
