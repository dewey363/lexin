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
use Admin\Model\StudentModel;

class PushController extends AdminbaseController {

    protected $push_model;
    protected $school_model;

    function _initialize() {
        parent::_initialize();
        $this->push_model = D("Admin/PushMessage");
        $this->school_model = D("Admin/School");
    }

    //消息推送列表
    public function index(){
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        $schoolSql=array();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['school']=array(
                    array('in',$schoolId)
                );
                $schoolSql['id']=array(
                    array('in',$schoolId)
                );
            }
        }
        /***获取管理员id,判断对应所属学校end***/
        $where['type']=3;
        $count=$this->push_model->where($where)->count();
        $page = $this->page($count, 20);
        $list = $this->push_model
            ->where($where)
            ->order("id DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        foreach ($list as $k=>$vo){
            $list[$k]['created_time']=date('Y-m-d H:i:s',$vo['created_time']);
            $schoolSql['id']=$vo['school'];
            $school=$this->school_model->where($schoolSql)->find();
            $list[$k]['school']=$school['name'];
        }
        $this->assign('list', $list);
        $this->assign("page", $page->show('Admin'));
        $this->display();
    }

    // 消息推送添加
    public function add(){
        $schoolSql=array();
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        $schoolSql=array();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $schoolSql['id']=array(
                    array('in',$schoolId)
                );
            }
        }
        /***获取管理员id,判断对应所属学校end***/
        $schoolList=$this->school_model->where($schoolSql)->select();
        $this->assign('schoolList', $schoolList);
        $this->display();
    }

    // 消息推送添加提交
    public function add_post(){
        if (IS_POST) {
            $school=$_POST['school'];
            $title=$_POST['title'];
            $content=$_POST['content'];
            $scope=$_POST['scope'];
            $type=3;
            $adminId=sp_get_current_admin_id();
            self::pushEasemob($title,$school,$content,$type,$adminId,$scope);
            $this->success("添加成功！",U("Push/index"));
        }
    }

    // 删除消息推送
    public function delete(){
        $id = I("get.id",0,"intval");
        if ($this->push_model->delete($id)!==false) {
            $this->success("删除成功！",U("Push/index"));
        } else {
            $this->error("删除失败！");
        }
    }

    public function pushEasemob($title,$school,$content,$type,$adminId,$scope)
    {
        //定义一个要发送的目标URL；
        $url = "http://111.231.63.219:6017/api/v1/class/addPush";
        //定义传递的参数数组；
//        content,pushType,userId,school,scope
        $data['title']=$title;
        $data['content']=$content;
        $data['pushType']=$type;
        $data['userId']=$adminId;
        $data['school']=$school;
        $data['scope']=$scope;
        //定义返回值接收变量；
        StudentModel::http($url, $data,"POST");

    }

}