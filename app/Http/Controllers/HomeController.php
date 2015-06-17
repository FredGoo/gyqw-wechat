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
    var_dump($_POST);
  }
}
