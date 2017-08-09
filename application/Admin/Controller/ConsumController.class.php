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
    
    //课时消耗列表
    public function index(){
        $where=array();
        $request=I('request.');
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['school_id']=array(
                    array('in',$schoolId)
                );
                $staffSql['school_id']=array(
                    array('in',$schoolId)
                );
                $classSql['school']=array(
                    array('in',$schoolId)
                );
                $classSql1['school']=array(
                    array('in',$schoolId)
                );
                $classSql2['school']=array(
                    array('in',$schoolId)
                );
                $stuSql['school']=array(
                    array('in',$schoolId)
                );
                $stuSql1['school']=array(
                    array('in',$schoolId)
                );
                $contractSql['school']=array(
                    array('in',$schoolId)
                );
                $contractSql1['school']=array(
                    array('in',$schoolId)
                );
            }
        }
        /***获取管理员id,判断对应所属学校end***/
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $stuSql['name']  = array('like', "%$keyword%");
            $stuSql['status']  = 0;
            $stuSql['is_del']  = 0;
            $stuId=D('Students')->where($stuSql)->field('id')->select();

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
            $classSql['is_del']=0;
            $classSql['teacher']=$request['teacher'];
            $classId= $this->class_model->where($classSql)->field('id')->select();
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
            $classSql1['id']=$v['class_id'];
            $class=$this->class_model->where($classSql1)->field('name,course,teacher')->find();
            $stuSql1['id']=$v['stu_id'];
            $student=D('Students')->where($stuSql1)->field('name')->find();
            $list[$k]['add_time']=date("Y-m-d H:i:s",$v['add_time']);
            $list[$k]['student_name']=$student['name'];
            $list[$k]['class_name']=$class['name'];
            $list[$k]['course']=$class['course'];
            $contractSql['card_info']=$v['card_info'];
            $contract=$this->studentContract_model->where($contractSql)->find();
            $list[$k]['price']=$contract['price'];
            $staffSql['position']=1;
            $staffSql['status']=0;
            $staffSql['id']=$class['teacher'];
            $teacher= $this->staff_model->where($staffSql)->find();
            $list[$k]['teacher_name']=$teacher['name'];
        }
        $listInfo = $class_consum
            ->where($where)
            ->order("add_time DESC")
            ->select();
        $allHour=0;
        $totalPrice=0;
        foreach ($listInfo as $k=>$v){
            $contractSql1['card_info']=$v['card_info'];
            $contract=$this->studentContract_model->where($contractSql1)->find();
            $totalPrice=$totalPrice+$v['class_hour']*$contract['price'];
            $allHour=$allHour+$v['class_hour'];
        }
        $staffSql['position']=1;
        $staffSql['status']=0;
        $teacher= $this->staff_model->where($staffSql)->select();
        $classSql2['is_del']=0;
        $class= $this->class_model->where($classSql2)->select();
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
            /***获取管理员id,判断对应所属学校start***/
            $adminId=sp_get_current_admin_id();
            if($adminId !=1){
                $schoolId=get_current_school();
                if(!empty($schoolId)){
                    $where['school_id']=array(
                        array('in',$schoolId)
                    );
                    $staffSql['school_id']=array(
                        array('in',$schoolId)
                    );
                    $classSql['school']=array(
                        array('in',$schoolId)
                    );
                    $classSql1['school']=array(
                        array('in',$schoolId)
                    );
                    $classSql2['school']=array(
                        array('in',$schoolId)
                    );
                    $stuSql['school']=array(
                        array('in',$schoolId)
                    );
                    $stuSql1['school']=array(
                        array('in',$schoolId)
                    );
                    $contractSql['school']=array(
                        array('in',$schoolId)
                    );
                    $contractSql1['school']=array(
                        array('in',$schoolId)
                    );
                }
            }
            /***获取管理员id,判断对应所属学校end***/
            if(!empty($request['keyword'])){
                $keyword=$request['keyword'];
                $stuSql['name']  = array('like', "%$keyword%");
                $stuSql['status']  = 0;
                $stuSql['is_del']  = 0;
                $stuId=D('Students')->where($stuSql)->field('id')->select();

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
                $classSql['is_del']=0;
                $classSql['teacher']=$request['teacher'];
                $classId= $this->class_model->where($classSql)->field('id')->select();
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
                $classSql1['id']=$v['class_id'];
                $class=$this->class_model->where($classSql1)->field('name,course,teacher')->find();
                $stuSql1['id']=$v['stu_id'];
                $student=D('Students')->where($stuSql1)->field('name')->find();
                $list[$k]['add_time']=date("Y-m-d H:i:s",$v['add_time']);
                $list[$k]['student_name']=$student['name'];
                $list[$k]['class_name']=$class['name'];
                $list[$k]['course']=$class['course'];
                $contractSql['card_info']=$v['card_info'];
                $contract=$this->studentContract_model->where($contractSql)->find();
                $list[$k]['price']=$contract['price'];
                $staffSql['position']=1;
                $staffSql['status']=0;
                $staffSql['id']=$class['teacher'];
                $teacher= $this->staff_model->where($staffSql)->find();
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
