<?php
// +----------------------------------------------------------------------
// | WinLangCMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.winlang.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: waj <63928427@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class SchoolController extends AdminbaseController {
	
	protected $school_model;

	function _initialize() {
		parent::_initialize();
		$this->school_model = D("Admin/School");
	}
	
	//学校列表
    public function index(){
        $count=$this->school_model->count();
        $page = $this->page($count, 20);

        $list = $this->school_model
            ->order("id DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign('list', $list);
        $this->assign("page", $page->show('Admin'));

        $this->display();
	}
	
	// 学校添加
	public function add(){
	 	$this->display();
	}
	
	// 学校添加提交
	public function add_post(){
		if (IS_POST) {
		    $_POST['created_at']=time();
		    $_POST['updated_at']=time();
			if ($this->school_model->create()!==false) {
				if ($this->school_model->add()!==false) {
					$this->success("添加成功！",U("School/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->school_model->getError());
			}
		}
	}
	
	// 学校编辑
	public function edit(){
		$id = I("get.id",0,'intval');
		$info=$this->school_model->where(array("id" => $id))->find();
		$this->assign("info",$info);
		$this->display();
	}
	
	// 学校编辑提交
	public function edit_post(){
        if (IS_POST) {
            $school['id']=intval($_POST['id']);
            $info=$this->school_model->where(array("id"=>$school['id']))->find();
            if(!empty($info)){
                $school['name']=I('name',$info['name']);
                $school['manage']=I('manage',$info['manage']);
                $school['listorder']=I('listorder',$info['listorder']);
                $school['auth_user']=I('auth_user',$info['auth_user']);
                $school['updated_at']=time();

                $result=$this->school_model->save($school);
                if ($result!==false) {
                    $this->success("保存成功！");
                } else {
                    $this->error("保存失败！");
                }
            }else{
                $this->error("记录不存在！");
            }

        }
	}
	
	// 删除学校
    public function delete(){
        $id = I("get.id",0,"intval");
        if ($this->school_model->delete($id)!==false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
	
}