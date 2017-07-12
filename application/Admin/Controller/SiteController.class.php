<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class SiteController extends AdminbaseController{
    
	protected $site_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->site_model = D("Common/License");
	}
	
	// 后台授权列表
	public function index(){
		$sites=$this->site_model->select();
		$this->assign("sites",$sites);
		$this->display();
	}
	
	// 授权添加
	public function add(){
		$this->display();
	}
	
	// 授权添加提交
	public function add_post(){
		if(IS_POST){
			if ($this->site_model->create()!==false){
				if ($this->site_model->add()!==false) {
					$this->success(L('ADD_SUCCESS'), U("site/index"));
				} else {
					$this->error(L('ADD_FAILED'));
				}
			} else {
				$this->error($this->site_model->getError());
			}
		
		}
	}
	
	// 授权编辑
	public function edit(){
		$id=I("get.id",0,'intval');
		$ad=$this->site_model->where(array('ad_id'=>$id))->find();
		$this->assign($ad);
		$this->display();
	}
	
	// 授权编辑提交
	public function edit_post(){
		if (IS_POST) {
			if ($this->site_model->create()!==false) {
				if ($this->site_model->save()!==false) {
					$this->success("保存成功！", U("site/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->site_model->getError());
			}
		}
	}
	
	// 授权删除
	public function delete(){
		$id = I("get.id",0,"intval");
		if ($this->site_model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}
	}
	
	// 授权显示/隐藏
	public function toggle(){
		if(!empty($_POST['ids']) && isset($_GET["display"])){
			$ids = I('post.ids/a');
			if ($this->site_model->where(array('ad_id'=>array('in',$ids)))->save(array('status'=>1))!==false) {
				$this->success("显示成功！");
			} else {
				$this->error("显示失败！");
			}
		}
		
		if(isset($_POST['ids']) && isset($_GET["hide"])){
			$ids = I('post.ids/a');
			if ($this->site_model->where(array('ad_id'=>array('in',$ids)))->save(array('status'=>0))!==false) {
				$this->success("隐藏成功！");
			} else {
				$this->error("隐藏失败！");
			}
		}
	}
	
}