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
   * 申请一个赞
   *
   * @return void
   *
   */
  public function applyZan(){
    return view('applyZan');
  }

  /**
   * 申请一个赞
   *
   * @return void
   *
   */
  public function submitApplyZan(){
    $title = \Request::input('title'); // 赞的标题
    $num = \Request::input('num'); // 赞的数量
    $content = \Request::input('content'); // 赞的内容
    $date = date('Y-m-d H:i:s');

    if($title && $num && $content){
      // 插入数据
      $res = \DB::insert('insert into zan.`order` (title, num, content, from_user, to_user, created_at, updated_at) values ("?", ?, "?", ?, ?, "?", "?")', [$title, $num, $content, 11, 22, $date, $date]);

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
}
