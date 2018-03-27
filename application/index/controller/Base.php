<?php
namespace app\index\controller;
use think\Controller;
/**
* 
*/
class Base extends Controller
{
	public function _empty() {
        $this->redirect("index/index");
    }

	public function category(){
		if(cookie('username')){
			session('username',cookie('username'));
		}
		$username = session('username');
		$this->assign('username',$username);
	}
}



?>