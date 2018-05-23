<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User;
/**
* 
*/
class Form extends Base
{

	public function login(){
		$post = $_POST;
		$user = User::get([
			'user_name' => $post['username'],
		]);
		if($user){
			if($user->password == $post['password']){
				$user->login_last_time = time();
				$user->login_times++;
				if(false !== $user->save()){
					session('uid',$user->id);
					session('username',$post['username']);
					cookie('uid',$user->id,3600);
		            cookie("username", $post['username'], 3600);
					$this->success('登录成功','index/index');
				}	
			}else{
				$this->error('密码错误','index/login');
			}
		}else{
			$this->error('用户不存在','index/login');
		}
	}

	public function logout(){
		session('username',null);
		session('uid',null);
		cookie('username',null);
		cookie('uid',null);
		if(session('username')){
			$this->error('注销失败','index/index');
		}else{
			$this->success('注销成功','index/index');
		}
	}

	public function sign(){
		$post = $_POST;
        $user = new User;
        $user->user_name = $post['username'];
        $user->password = $post['password'];
        $user->email = $post['email'];
        if($user->save()) {                                               
            $this->success('注册成功！','index/login');                           
        }else{         
            $this->error('注册失败！','index/sign');
        }
	}
}



?>