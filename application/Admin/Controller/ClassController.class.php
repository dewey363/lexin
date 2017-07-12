<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Admin\Model\ClassModel;
use Admin\Model\StudentModel;

class ClassController extends AdminbaseController{


    protected $school_model;
    protected $class_model;
    protected $staff_model;
    public function _initialize() {
        parent::_initialize();
        $this->school_model = D("Admin/School");
        $this->staff_model = D("Admin/Staff");
        $this->class_model = D("Admin/Class");
    }
    
    //班级管理列表
    public function index(){
        $where=array();
        $request=I('request.');

        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['name']  = array('like', "%$keyword%");
            $keyword_complex['course']  = array('like',"%$keyword%");
//          $keyword_complex['mobile']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
    	$count=$this->class_model->where($where)->count();
    	$page = $this->page($count, 20);
    	
    	$list = $this->class_model
    	->where($where)
    	->order("id DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	foreach ($list as $k=>$v){
    	    $list[$k]['open_date']=date("Y-m-d",$v['open_date']);
    	    $list[$k]['class_time']=date("Y-m-d",$v['class_time']);
            $course_consultant = D('staff')->where(array('id'=>$v['teacher'],'position'=>1))->find();
            $list[$k]['teacher']=$course_consultant['name'];
        }
    	$this->assign('list', $list);
    	$this->assign("page", $page->show('Admin'));
    	$this->display();
    }

    //新增班级
    public function add(){
        $schoolList=$this->school_model->select();
        $teacherList=$this->staff_model->where(array("position"=>1))->select();
        $studentList=D("Students")->where(array("class"=>0))->select();
        $this->assign("studentList",$studentList);
        $this->assign("schoolList",$schoolList);
        $this->assign("teacherList",$teacherList);
        $this->display();
    }

    //新增班级提交
    public function add_post(){
        if (IS_POST) {
            $class['name']=I('name','');
            $class['course']=I('course','');
            $class['teacher']=I('teacher',0);
            $class['open_date']=strtotime(I('open_date'));
            $class['class_time']=strtotime(I('class_time'));
            $class['week_day']=I('week_day',"");
            $class['student_population']=I('student_population',0);
            $class['status']=I('status',0);
            $class['holiday']=I('holiday',0);
            $class['times']=I('times',0);
            $class['number']=I('number',0);
            $class['school']=I('school',0);
            $class['consume_times']=I('consume_times',1);
            $class['stu_id']=I('studentIds',"");

            $result=$this->class_model->add($class);
            if ($result) {
                if(!empty($class['stu_id'])){
                    $stuIds=explode(",",$class['stu_id']);
                    $classStudent=M('class_student');
                    foreach ($stuIds as $v){
                        $data['stu_id']=$v;
                        $data['class_id']=$class['id'];
                        $classStudent->add($data);

                        $stuArr['class']=$class['id'];
                        $stuArr['id']=$v['id'];
                        D('students')->save($stuArr);

                    }
                }
                $this->success("添加成功！",U("class/index"));
            } else {
                $this->error("添加失败！");
            }

        }
    }

    //班级编辑
    public function edit(){
        $id=  I("get.id",0,'intval');
        $info=$this->class_model->where("id=$id")->find();
        $info['open_date']=date("Y-m-d",$info['open_date']);
        $info['class_time']=date("Y-m-d",$info['class_time']);
        $schoolList=$this->school_model->select();
        foreach ($schoolList as $k=>$v){
            if($v['id']==$info['school']){
                $schoolList[$k]['selected']="selected";
            }else{
                $schoolList[$k]['selected']="";
            }
        }
        $teacherList=$this->staff_model->where(array("position"=>1))->select();
        foreach ($teacherList as $k=>$v){
            if($v['id']==$info['teacher']){
                $teacherList[$k]['selected']="selected";
            }else{
                $teacherList[$k]['selected']="";
            }
        }
        $stuIds=[];
        $where['class']=0;
        if(!empty($info['stu_id'])){
            $where['id']=array('not in',$info['stu_id']);
            $sql['id']=array('in',$info['stu_id']);
            $stuIds=D("Students")->where($sql)->field("id,name")->select();
        }
        $studentList=D("Students")->where($where)->select();
        $this->assign("stuIds",$stuIds);
        $this->assign("studentList",$studentList);
        $this->assign("schoolList",$schoolList);
        $this->assign("teacherList",$teacherList);
        $this->assign("info",$info);
        $this->assign("id",$id);
        $this->display();
    }

    //班级编辑提交
    public function edit_post(){
        if (IS_POST) {
            $class['id']=intval($_POST['id']);
            $info=$this->class_model->where(array("id"=>$class['id']))->find();
            if(!empty($info)){
                $class['name']=I('name',$info['name']);
                $class['course']=I('course',$info['course']);
                $class['teacher']=I('teacher',$info['course']);
                $class['open_date']=strtotime(I('open_date'));
                $class['class_time']=strtotime(I('class_time'));
                $class['week_day']=I('week_day',$info['week_day']);
                $class['student_population']=I('student_population',$info['student_population']);
                $class['status']=I('status',$info['status']);
                $class['holiday']=I('holiday',$info['holiday']);
                $class['times']=I('times',$info['times']);
                $class['number']=I('number',$info['number']);
                $class['school']=I('school',$info['school']);
                $class['consume_times']=I('consume_times',$info['consume_times']);
                $class['stu_id']=I('studentIds',"");
                $classStudent=M('class_student');
                if(!empty($info['stu_id'])){
                    $classStudent->where(array("class_id"=>$class['id']))->delete();
                }
                $result=$this->class_model->save($class);
                if ($result!==false) {
                    if(!empty($class['stu_id'])){
                        $stuIds=explode(",",$class['stu_id']);
                        foreach ($stuIds as $v){
                            $data['stu_id']=$v;
                            $data['class_id']=$class['id'];
                            $classStudent->add($data);

                            $stuArr['class']=$class['id'];
                            $stuArr['id']=$v['id'];
                            D('students')->save($stuArr);
                        }
                    }
                    $this->success("保存成功！");
                } else {
                    $this->error("保存失败！");
                }
            }else{
                $this->error("记录不存在！");
            }

        }
    }
    // 授权删除
    public function delete(){
        $id = I("get.id",0,"intval");
        if ($this->class_model->delete($id)!==false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    //批量导出
    public function export(){
        if (IS_POST) {
            $where=[];
            if(isset($_POST['ids'])) {
                $ids = I('post.ids/a');
                $where['id']=array('in',$ids);
            }
                $list = $this->class_model->where($where)->order("id DESC")->select();
                if(!empty($list)){
                    $stuList=[];
                    foreach ($list as $k => $v) {
                        $list[$k]['open_date'] = date("Y-m-d", $v['apply_date']);
                        $stuList[]= D("Students")->where(array('id'=>array('in',$v['stu_id'])))->order("id DESC")->select();
                    }
                    $stuLists=[];
                    foreach ($stuList as $vo){
                        foreach ($vo as $v){
                            $stuLists[]=$v;
                        }
                    }
                    foreach ($stuLists as $k=>$v){
                        $course_consultant = D('staff')->where(array('id'=>$v['course_consultant'],'position'=>3))->find();
                        $stuLists[$k]['course_consultant']=$course_consultant['name'];
                        $list[$k]['parent_name']="";
                        $list[$k]['parent_phone']="";
                        $list[$k]['relationship']="";
                        $parentInfo=D('Parents')
                            ->where(array(
                                "stu_id"=>$v['id'],
                                "guardian"=>1
                            ))
                            ->order("id DESC")
                            ->limit(1)
                            ->find();
                        if(!empty($parentInfo)){
                            $list[$k]['parent_name']=$parentInfo['name'];
                            $list[$k]['parent_phone']=$parentInfo['phone'];
                            $list[$k]['relationship']=$parentInfo['relationship'];
                        }
                    }
                    ClassModel::exportList($stuLists);
                    exit;
                }
        }
    }

    //放假／取消放假
    public function holiday(){
        if(isset($_POST['ids']) && $_GET["holiday"]){
            $ids = I('post.ids/a');
            if ( $this->class_model->where(array('id'=>array('in',$ids)))->save(array('holiday'=>1)) !== false ) {
                $this->success("放假成功！");
            } else {
                $this->error("放假失败！");
            }
        }
        if(isset($_POST['ids']) && $_GET["unholiday"]){
            $ids = I('post.ids/a');
            if ( $this->class_model->where(array('id'=>array('in',$ids)))->save(array('holiday'=>0)) !== false) {
                $this->success("取消放假成功！");
            } else {
                $this->error("取消放假失败！");
            }
        }
    }

    // 学生管理列表
    public function student(){
        $classId=  I("get.classId",0,'intval');
        $where=array();
        $request=I('request.');
        $cid=0;
        if($classId>0){
            $where['_string']='FIND_IN_SET('.$classId.', class)';
            $cid=$classId;
        }elseif($request['classId']>0){
            $where['_string']='FIND_IN_SET('.$request['classId'].', class)';
            $cid=$request['classId'];
        }
        if(!empty($request['surplus_hour'])){
            $where['surplus_hour']=array('ELT',$request['surplus_hour']);
        }
        if(!empty($request['divide_class'])){
            if($request['divide_class']==1){
                $where['class']=array('exp','is not null');
            }elseif($request['divide_class']==2){
                $where['class']=array('exp','is null');
            }

        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['name']  = array('like', "%$keyword%");
            $keyword_complex['class_name']  = array('like',"%$keyword%");
            $keyword_complex['mobile']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $start_time=strtotime(I('request.start_time'));
        if(!empty($start_time)){
            $where['apply_date']=array(
                array('EGT',$start_time)
            );
        }

        $end_time=strtotime(I('request.end_time'));
        if(!empty($end_time)){
            if(empty($where['apply_date'])){
                $where['apply_date']=array();
            }
            array_push($where['apply_date'], array('ELT',$end_time));
        }

        $student_model=M("Students");

        $count=$student_model->where($where)->count();
        $page = $this->page($count, 20);

        $list = $student_model
            ->where($where)
            ->order("add_time DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        foreach ($list as $k=>$v){
            $course_consultant = D('staff')->where(array('id'=>$v['course_consultant'],'position'=>3))->find();
            $list[$k]['course_consultant']=$course_consultant['name'];
            $list[$k]['apply_date']=date("Y-m-d",$v['apply_date']);
            $list[$k]['class_name']="";
            if(!empty($v['class'])){
                $classList=$this->class_model
                    ->where(array("id"=>array("in",$v['class'])))
                    ->field("name")
                    ->select();
                $className=[];
                foreach ($classList as $vo){
                    $className[]=$vo['name'];
                }
                $list[$k]['class_name']= implode("<br/>",$className);
            }
        }
        $this->assign('list', $list);
        $this->assign('classId', $cid);
        $this->assign("page", $page->show('Admin'));

        $this->display();
    }

    //消耗课时记录列表
    public function classConsume(){
        $classId=  I("get.classId",0,'intval');
        $request=I('request.');
        $classIds=0;
        if($classId>0){
            $classIds=$classId;
        }elseif($request['classId']>0){
            $classIds=$request['classId'];
        }
        if($classIds>0){
            $where['class_id']=$classIds;
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
                $class=$this->class_model->where(array("id"=>$v['class_id']))->field('name,course')->find();
                $student=D('Students')->where(array("id"=>$v['stu_id']))->field('name')->find();
                $list[$k]['add_time']=date("Y-m-d H:i:s",$v['add_time']);
                $list[$k]['student_name']=$student['name'];
                $list[$k]['class_name']=$class['name'];
                $list[$k]['course']=$class['course'];
            }
            $this->assign('list', $list);
            $this->assign('classId', $classIds);
            $this->assign("page", $page->show('Admin'));
        }
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
}
