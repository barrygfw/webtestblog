<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\User;
use app\admin\model\Detail;
class admin extends Controller{

	public function _initialize(){
		if (!session('username')) {
			$this->redirect('index/index/index');
		}
	}

	public function admin(){
		$id = User::where('user_name',session('username'))->value('id');
		if($id == 1){
			
		}
		$res = Detail::where('uid',$id)->paginate(4);
		foreach ($res as $key => $value) {
			$category = Db::name('article')->where('id',$value->pid)->value('kind');
			$value->pid = $category;
		}
		// var_dump($res);
		$this->assign('article',$res);
		return $this->fetch();
	}

	public function addatc(){
		$category = Db::name('article') -> where('status',1)->select();
		$this->assign('category',$category);
		return $this->fetch();
	}

	public function addarticle(){
		$article = new Detail;
		$article->title = $_POST['title'];
		$article->content = $_POST['editorValue'];
		$article->pid = $_POST['category'];
		$article->introduce = $_POST['introduce'];
		$article->update_time = time();
		$id = User::where('user_name',session('username'))->value('id');
		$article->uid = $id;
		if($article->save()){
			$this->success('新增成功！','admin/admin/admin');
		}else{
			$this->error('新增失败！','admin/admin/addatc');
		}
	}

	public function delarticle($id){
		$article = Detail::get($id);
		if($article){
			$article->delete();
			$this->success('删除文章成功！','admin/admin/admin');
		}else{
			$this->error('删除文章失败！','admin/admin/admin');
		}
	}

	public function editarticle($id){
		$article = Detail::get($id);
		$category = Db::name('article') -> where('status',1)->select();
		$this->assign('category',$category);
		$this->assign('artdetail',$article);
		return $this->fetch('addatc');
	}

	public function updatearticle($id){
		$res = Detail::get($id);
		$res->title = $_POST['title'];
		$res->content = $_POST['editorValue'];
		$res->pid = $_POST['category'];
		$res->introduce = $_POST['introduce'];
		$res->update_time = time();
		$id = User::where('user_name',session('username'))->value('id');
		$res->uid = $id;
		if($res->save()){
			$this->success('编辑成功！','admin/admin/admin');
		}else{
			$this->error('编辑失败！','admin/admin/addatc');
		}
	}
}

