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

class StaffController extends AdminbaseController {

    protected $staff_model;
    protected $school_model;

    function _initialize() {
        parent::_initialize();
        $this->staff_model = D("Admin/Staff");
        $this->school_model = D("Admin/School");
    }

    //学校列表
    public function index(){
        $count=$this->staff_model->count();
        $page = $this->page($count, 20);

        $list = $this->staff_model
            ->order("id DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $this->assign('list', $list);
        $this->assign("page", $page->show('Admin'));

        $this->display();
    }

    // 学校添加
    public function add(){
        $schoolList=$this->school_model->select();
        $position=D('staff_position')->select();
        $this->assign("schoolList",$schoolList);
        $this->assign("position",$position);
        $this->display();
    }

    // 学校添加提交
    public function add_post(){
        if (IS_POST) {
            $_POST['created_at']=time();
            $_POST['updated_at']=time();
            if ($this->staff_model->create()!==false) {
                if ($this->staff_model->add()!==false) {
                    $this->success("添加成功！",U("Staff/index"));
                } else {
                    $this->error("添加失败！");
                }
            } else {
                $this->error($this->staff_model->getError());
            }
        }
    }

    // 学校编辑
    public function edit(){
        $id = I("get.id",0,'intval');
        $info=$this->staff_model->where(array("id" => $id))->find();
        $schoolList=$this->school_model->select();
        foreach ($schoolList as $k=>$v){
                if($v['id']==$info['school_id']){
                    $schoolList[$k]['selected']="selected";
                }else{
                    $schoolList[$k]['selected']="";
                }
        }
        $position=D('staff_position')->select();
        foreach ($position as $k=>$v){
            if($v['id']==$info['position']){
                $position[$k]['selected']="selected";
            }else{
                $position[$k]['selected']="";
            }
        }
        $this->assign("schoolList",$schoolList);
        $this->assign("position",$position);
        $this->assign("info",$info);
        $this->assign("id",$id);
        $this->display();
    }

    // 学校编辑提交
    public function edit_post(){
        if (IS_POST) {
            $staff['id']=intval($_POST['id']);
            $info=$this->staff_model->where(array("id"=>$staff['id']))->find();
            if(!empty($info)){
                $staff['name']=I('name',$info['name']);
                $staff['sex']=I('sex',$info['sex']);
                $staff['position']=I('position',$info['position']);
                $staff['phone']=I('phone',$info['phone']);
                $staff['identity_cards']=I('identity_cards',$info['identity_cards']);
                $staff['address']=I('address',$info['address']);
                $staff['emergency_contact']=I('emergency_contact',$info['emergency_contact']);
                $staff['emergency_call']=I('emergency_call',$info['emergency_call']);
                $staff['school_id']=I('school_id',$info['school_id']);
                $staff['number']=I('number',$info['number']);
                $staff['updated_at']=time();

                $result=$this->staff_model->save($staff);
                if ($result!==false) {
                    $this->success("保存成功！",U("Staff/index"));
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
        if ($this->staff_model->delete($id)!==false) {
            $this->success("删除成功！",U("Staff/index"));
        } else {
            $this->error("删除失败！");
        }
    }

}