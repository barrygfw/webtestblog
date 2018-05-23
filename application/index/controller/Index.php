<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Detail;
use app\index\model\User;
use app\index\model\Comment;
class Index extends Base
{
    public function _initialize() {
        Parent::initialize();
    }
    public function index()
    {
    	$tec_title = Db::name('detail')
    		->where('pid',1)
    		->limit(5)
    		->select();
    	$xin_title = Db::name('detail')
    		->where('pid',2)
    		->limit(5)
    		->select();
    	$visit_title = Db::name('detail')
    		->where('pid',3)
    		->limit(2)
    		->select();
    	$this->assign('tec_title',$tec_title);
    	$this->assign('xin_title',$xin_title);
    	$this->assign('visit_title',$visit_title);
        return $this->fetch();
    }

    public function article($p_id){
    	$list = Detail::where('pid',$p_id)->paginate(5,false,['query' => ['p_id'=>$p_id]]);
        $kind = Db::name('article')
            ->where('id',$p_id)
            ->field('kind')
            ->find();
        foreach ($list as $key => $value) {
            $uid = $value->uid;
            $author = User::where('id',$uid)->value('user_name');
            $value->uid = $author;
        }
    	$this->assign('list',$list);
        $this->assign('kind',$kind);
    	return $this->fetch();
    }

    public function detail($id){
        $detail = Detail::where('id',$id)->select();
        foreach ($detail as $key => $value) {
            $uid = $value->uid;
            $author = User::where('id',$uid)->value('user_name');
            $value->uid = $author;
        }
        $this->assign('detail',$detail);
    	return $this->fetch();
    }

    public function login(){
        return $this->fetch();
    }

    public function sign(){
        return $this->fetch();
    }
    public function getsearch(){
        $val = $_POST['title'];
        $list = Detail::where('title','like','%'.$val.'%')->paginate(5);
        foreach ($list as $key => $value) {
            $uid = $value->uid;
            $author = User::where('id',$uid)->value('user_name');
            $value->uid = $author;
        }
        $this->assign('list',$list);
        $kind['kind'] = '查询';
        $this->assign('kind',$kind);
        return $this->fetch('article');
    }
    public function getcomment(){
        $aid = $_POST['aid'];
        $res = Db::name('comment')->where(['status'=>1,'aid'=>$aid])->select();
        foreach ($res as $key => $value) {
            $uid = $value['uid'];
            $name=Db::name('user')->where(['id'=>$uid])->value('user_name');
            $res[$key]['name']=$name;
        }
        return $res;
    }
    public function subcomment(){
        $val = $_POST;
        $val['create_time'] = time();
        $res = Comment::create($val);
        return $res;
    }
}
