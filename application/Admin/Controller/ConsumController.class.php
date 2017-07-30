<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Admin\Model\ClassModel;

class ConsumController extends AdminbaseController{


    protected $school_model;
    protected $class_model;
    protected $staff_model;
    protected $studentContract_model;
    public function _initialize() {
        parent::_initialize();
        $this->school_model = D("Admin/School");
        $this->class_model = D("Admin/Class");
        $this->staff_model = D("Admin/Staff");
        $this->studentContract_model = D("Admin/StudentContract");
    }
    
    //班级管理列表
    public function index(){
        $where=array();
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $stu_where['name']  = array('like', "%$keyword%");
            $stu_where['status']  = 0;
            $stu_where['is_del']  = 0;
            $stuId=D('Students')->where($stu_where)->field('id')->select();

            $stuIdArr=[];
            if(!empty($stuId)){
                foreach ($stuId as $v){
                    $stuIdArr[]=$v['id'];
                }
            }
            if(!empty($stuIdArr)){
                $stuIds=implode(",",$stuIdArr);
                $where['stu_id']=array(
                    array('in',$stuIds)
                );
            }
        }
        if($request['class_id']>0){
            $where['class_id']=$request['class_id'];
        }
        if($request['teacher']>0){
            $classId= $this->class_model->where(array('is_del'=>0,'teacher'=>$request['teacher']))->field('id')->select();
            $classIdArr=[];
            if(!empty($classId)){
                foreach ($classId as $v){
                    $classIdArr[]=$v['id'];
                }
            }
            if(!empty($classIdArr)){
                $classIds=implode(",",$classIdArr);
                $where['class_id']=array(
                    array('in',$classIds)
                );
            }

        }
        $start_time=strtotime($request['start_time']);
        if(!empty($start_time)){
            $where['add_time']=array(
                array('EGT',$start_time)
            );
        }

        $end_time=strtotime($request['end_time']);
        if(!empty($end_time)){
            if(empty($where['add_time'])){
                $where['add_time']=array();
            }
            array_push($where['add_time'], array('ELT',$end_time));
        }

        $class_consum=M("class_consum");

        $count=$class_consum->where($where)->count();
        $page = $this->page($count, 20);

        $list = $class_consum
            ->where($where)
            ->order("add_time DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        foreach ($list as $k=>$v){
            $class=$this->class_model->where(array("id"=>$v['class_id']))->field('name,course,teacher')->find();
            $student=D('Students')->where(array("id"=>$v['stu_id']))->field('name')->find();
            $list[$k]['add_time']=date("Y-m-d H:i:s",$v['add_time']);
            $list[$k]['student_name']=$student['name'];
            $list[$k]['class_name']=$class['name'];
            $list[$k]['course']=$class['course'];
            $contract=$this->studentContract_model->where(array("card_info"=>$v['card_info']))->find();
            $list[$k]['price']=$contract['price'];
            $teacher= $this->staff_model->where(array('position'=>1,'status'=>0,'id'=>$class['teacher']))->find();
            $list[$k]['teacher_name']=$teacher['name'];
        }
        $listInfo = $class_consum
            ->where($where)
            ->order("add_time DESC")
            ->select();
        $allHour=0;
        $totalPrice=0;
        foreach ($listInfo as $k=>$v){
            $contract=$this->studentContract_model->where(array("card_info"=>$v['card_info']))->find();
            $totalPrice=$totalPrice+$v['class_hour']*$contract['price'];
            $allHour=$allHour+$v['class_hour'];
        }
        $teacher= $this->staff_model->where(array('position'=>1,'status'=>0))->select();
        $class= $this->class_model->where(array('is_del'=>0))->select();
        $this->assign('list', $list);
        $this->assign('teacher', $teacher);
        $this->assign('class', $class);
        $this->assign('totalPrice', $totalPrice);
        $this->assign('allHour', $allHour);
        $this->assign("page", $page->show('Admin'));

        $this->display();
    }

    // 授权删除
    public function consumeDel(){
        $id = I("get.id",0,"intval");
        $class_consume=M("class_consum");
        if ($class_consume->delete($id)!==false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    //批量导出
    public function exportConsum(){
        if (IS_POST) {
            $where=array();
            $request=I('request.');
            if(!empty($request['keyword'])){
                $keyword=$request['keyword'];
                $stu_where['name']  = array('like', "%$keyword%");
                $stu_where['status']  = 0;
                $stu_where['is_del']  = 0;
                $stuId=D('Students')->where($stu_where)->field('id')->select();

                $stuIdArr=[];
                if(!empty($stuId)){
                    foreach ($stuId as $v){
                        $stuIdArr[]=$v['id'];
                    }
                }
                if(!empty($stuIdArr)){
                    $stuIds=implode(",",$stuIdArr);
                    $where['stu_id']=array(
                        array('in',$stuIds)
                    );
                }
            }
            if($request['class_id']>0){
                $where['class_id']=$request['class_id'];
            }
            if($request['teacher']>0){
                $classId= $this->class_model->where(array('is_del'=>0,'teacher'=>$request['teacher']))->field('id')->select();
                $classIdArr=[];
                if(!empty($classId)){
                    foreach ($classId as $v){
                        $classIdArr[]=$v['id'];
                    }
                }
                if(!empty($classIdArr)){
                    $classIds=implode(",",$classIdArr);
                    $where['class_id']=array(
                        array('in',$classIds)
                    );
                }

            }
            $start_time=strtotime($request['start_time']);
            if(!empty($start_time)){
                $where['add_time']=array(
                    array('EGT',$start_time)
                );
            }

            $end_time=strtotime($request['end_time']);
            if(!empty($end_time)){
                if(empty($where['add_time'])){
                    $where['add_time']=array();
                }
                array_push($where['add_time'], array('ELT',$end_time));
            }

            $class_consum=M("class_consum");

            $count=$class_consum->where($where)->count();
            $page = $this->page($count, 20);

            $list = $class_consum
                ->where($where)
                ->order("add_time DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
            foreach ($list as $k=>$v){
                $class=$this->class_model->where(array("id"=>$v['class_id']))->field('name,course,teacher')->find();
                $student=D('Students')->where(array("id"=>$v['stu_id']))->field('name')->find();
                $list[$k]['add_time']=date("Y-m-d H:i:s",$v['add_time']);
                $list[$k]['student_name']=$student['name'];
                $list[$k]['class_name']=$class['name'];
                $list[$k]['course']=$class['course'];
                $contract=$this->studentContract_model->where(array("card_info"=>$v['card_info']))->find();
                $list[$k]['price']=$contract['price'];
                $teacher= $this->staff_model->where(array('position'=>1,'status'=>0,'id'=>$class['teacher']))->find();
                $list[$k]['teacher']=$teacher['name'];
                if($v['type']==0){
                    $list[$k]['type']="正常上课";
                }elseif($v['type']==1){
                    $list[$k]['type']="补课";
                }elseif($v['type']==2){
                    $list[$k]['type']="缺课";
                }
            }
            ClassModel::exportConsum($list,$count);
            exit;
        }
    }
}
