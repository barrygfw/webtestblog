<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Detail;
use app\index\model\User;
class Index extends Base
{
    public function _initialize() {
        Parent::category();
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
    	$list = Db::name('detail')
    		->where('pid',$p_id)
    		->paginate(5,false,['query' => ['p_id'=>$p_id]]);
        $kind = Db::name('article')
            ->where('id',$p_id)
            ->field('kind')
            ->find();
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
}
