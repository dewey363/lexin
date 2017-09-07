<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Admin\Model\ClassModel;

class ClassController extends AdminbaseController{


    protected $school_model;
    protected $class_model;
    protected $staff_model;
    protected $studentContract_model;
    public function _initialize() {
        parent::_initialize();
        $this->school_model = D("Admin/School");
        $this->staff_model = D("Admin/Staff");
        $this->class_model = D("Admin/Class");
        $this->studentContract_model = D("Admin/StudentContract");
    }
    
    //班级管理列表
    public function index(){
        $where=array();
        $request=I('request.');
        $adminId=sp_get_current_admin_id();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['school']=array(
                    array('in',$schoolId)
                );
            }
        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['name']  = array('like', "%$keyword%");
            $keyword_complex['course']  = array('like',"%$keyword%");
//          $keyword_complex['mobile']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        if($request['teacher'] >0){
            $where['teacher']=$request['teacher'];
        }

        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['school']=array(
                    array('in',$schoolId)
                );
                $staffSql['school_id']=array(
                    array('in',$schoolId)
                );
                $classSql['school']=array(
                    array('in',$schoolId)
                );
            }
        }
        /***获取管理员id,判断对应所属学校end***/

    	$count=$this->class_model->where($where)->count();
    	$page = $this->page($count, 20);
    	
    	$list = $this->class_model
    	->where($where)
    	->order("id DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	foreach ($list as $k=>$v){
    	    $list[$k]['open_date']=date("Y-m-d",$v['open_date']);
            $staffSql['position']=1;
            $staffSql['status']=0;
            $staffSql['id']=$v['teacher'];
            $course_consultant = $this->staff_model->where($staffSql)->find();
            $list[$k]['teacher']=$course_consultant['name'];
            $list[$k]['holiday']=$v['holiday']==1? '是':'否';
        }
        $staffSql['position']=1;
        $staffSql['status']=0;
        $teacher= $this->staff_model->where($staffSql)->select();
    	$this->assign('list', $list);
    	$this->assign('teacher', $teacher);
    	$this->assign("page", $page->show('Admin'));
    	$this->display();
    }

    //新增班级
    public function add(){
        $adminId=sp_get_current_admin_id();
        $schoolSql=array();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $staffSql['school_id']=array(
                    array('in',$schoolId)
                );

                $schoolSql['id']=array(
                    array('in',$schoolId)
                );
            }
        }
        $staffSql['position']=1;
        $staffSql['status']=0;
        $schoolList=$this->school_model->where($schoolSql)->select();
        $teacherList=$this->staff_model->where($staffSql)->select();

        $where['is_del']=0;
        $contractIds=D('ClassStudent')->where($where)->field('contract_id')->select();
        $contractId=array();
        if(!empty($contractIds)){
            foreach ($contractIds as $v){
                $contractId[]=$v['contract_id'];
            }
        }
        if(!empty($contractId)){
            $arr['id']=array(
                array('not in',implode(',',$contractId))
            );
        }
        $arr['status']=2;
        $arr['is_del']=0;
        $contractList=$this->studentContract_model->where($arr)->field('id,name,stu_id')->select();
        foreach ($contractList as $k=>$v){
            $studentInfo=D("Students")->where(array("id"=>$v['stu_id']))->field('id,name')->find();
            $contractList[$k]['stu_name']=$v['name']."_".$studentInfo['name'];
        }

        $this->assign("contractList",$contractList);
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
            $class['class_time']=I('class_time');
            $class['week_day']=I('week_day',"");
            $class['student_population']=I('student_population',0);
//            $class['status']=I('status',0);
//            if($class['status']==1){
//                $class['closed_time']=time();
//            }
//
//            $class['holiday']=I('holiday',0);
//
//            if($class['holiday']==1){
//                $class['vacation_time']=time();
//            }
            $class['times']=I('times',0);
            $class['number']=I('number',0);
            $class['school']=I('school',0);
            $class['hour']=I('hour',0);
            $class['consume_times']=I('consume_times',1);
            $contractId=I('contractIds',"");

            $result=$this->class_model->add($class);
            if ($result) {
                if(!empty($contractId)){
                    $stuIds=explode(",",$contractId);
                    $classStudent=M('ClassStudent');
                    foreach ($stuIds as $v){
                        $data['contract_id']=$v;
                        $data['class_id']=$class['id'];
                        $data['status']=1;
                        $classStudent->add($data);

                        $conWhere['id']=$v;
                        $contract['class']=$class['id'];
                        $this->studentContract_model->where($conWhere)->save($contract);
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
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        $schoolSql=array();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $staffSql['school_id']=array(
                    array('in',$schoolId)
                );

                $schoolSql['id']=array(
                    array('in',$schoolId)
                );
            }
        }
        $staffSql['position']=1;
        $staffSql['status']=0;
        /***获取管理员id,判断对应所属学校end***/
        $info['open_date']=date("Y-m-d",$info['open_date']);
        $schoolList=$this->school_model->where($schoolSql)->select();
        foreach ($schoolList as $k=>$v){
            if($v['id']==$info['school']){
                $schoolList[$k]['selected']="selected";
            }else{
                $schoolList[$k]['selected']="";
            }
        }
        $teacherList=$this->staff_model->where($staffSql)->select();
        foreach ($teacherList as $k=>$v){
            if($v['id']==$info['teacher']){
                $teacherList[$k]['selected']="selected";
            }else{
                $teacherList[$k]['selected']="";
            }
        }

        $where['is_del']=0;
        $contractIds=D('ClassStudent')->where($where)->field('contract_id')->select();
        $contractId=array();
        if(!empty($contractIds)){
            foreach ($contractIds as $v){
                $contractId[]=$v['contract_id'];
            }
        }
        if(!empty($contractId)){
            $arr['id']=array(
                array('not in',implode(',',$contractId))
            );
        }
        $arr['status']=2;
        $arr['is_del']=0;
        $contractList=$this->studentContract_model->where($arr)->field('id,name,stu_id')->select();
        foreach ($contractList as $k=>$v){
            $studentInfo=D("Students")->where(array("id"=>$v['stu_id']))->field('id,name')->find();
            $contractList[$k]['stu_name']=$v['name']."_".$studentInfo['name'];
        }

        $contractSaveIds=array();
        $sql['is_del']=0;
        $sql['class_id']=$id;
        $csList=D('ClassStudent')->where($sql)->field('contract_id')->select();
        $contractSaveId=array();
        if(!empty($csList)){
            foreach ($csList as $v){
                $contractSaveId[]=$v['contract_id'];
            }
        }

        if(!empty($contractSaveId)){
            $sql['id']=array('in',implode(',',$contractSaveId));
            $contractSaveIds=$this->studentContract_model->where($sql)->select();
            foreach ($contractSaveIds as $k=>$v){
                $studentInfo=D("Students")->where(array("id"=>$v['stu_id']))->field('id,name')->find();
                $contractSaveIds[$k]['stu_name']=$v['name']."_".$studentInfo['name'];
            }
        }
        $this->assign("contractList",$contractList);
        $this->assign("contractIds",$contractSaveIds);
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
                $class['class_time']=I('class_time',$info['class_time']);
                $class['week_day']=I('week_day',$info['week_day']);
                $class['student_population']=I('student_population',$info['student_population']);

//                $class['status']=I('status',$info['status']);
//                if($class['status']==1){
//                    $class['closed_time']=time();
//                }else{
//                    $class['closed_time']=0;
//                }
//
//                $class['holiday']=I('holiday',$info['holiday']);
//
//                if($class['holiday']==1){
//                    $class['vacation_time']=time();
//                }else{
//                    $class['vacation_time']=0;
//                }

                $class['times']=I('times',$info['times']);
                $class['number']=I('number',$info['number']);
                $class['school']=I('school',$info['school']);
                $class['hour']=I('hour',$info['hour']);
                $class['consume_times']=I('consume_times',$info['consume_times']);
                $contractId=I('contractIds',"");
                $classStudent=M('class_student');
                if(!empty($info['contract_id'])){
                    $classStudent->where(array("class_id"=>$class['id']))->delete();
                }
                $result=$this->class_model->save($class);
                if ($result!==false) {
                    if(!empty($contractId)){
                        $contractIds=explode(",",$contractId);
                        foreach ($contractIds as $v){
                            $data['contract_id']=$v;
                            $data['class_id']=$class['id'];
                            $classStudent->add($data);

                            $conWhere['id']=$v;
                            $contract['class']=$class['id'];
                            $this->studentContract_model->where($conWhere)->save($contract);
                        }
                    }
                    $this->success("保存成功！");
                }else{
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
            $where=array();
            if(isset($_POST['ids'])) {
                $ids = I('post.ids/a');
                $where['id']=array('in',$ids);
            }
            /***获取管理员id,判断对应所属学校start***/
            $adminId=sp_get_current_admin_id();
            if($adminId !=1){
                $schoolId=get_current_school();
                if(!empty($schoolId)){
                    $where['school']=array(
                        array('in',$schoolId)
                    );
                    $stuSql['school']=array(
                        array('in',$schoolId)
                    );
                    $staffSql['school_id']=array(
                        array('in',$schoolId)
                    );
                }
            }
            /***获取管理员id,判断对应所属学校end***/
                $list = $this->class_model->where($where)->order("id DESC")->select();
                if(!empty($list)){
                    $stuList=array();
                    foreach ($list as $k => $v) {
                        $list[$k]['open_date'] = date("Y-m-d", $v['apply_date']);
                        $stuSql['id']=array('in',$v['stu_id']);
                        $stuList[]= D("Students")->where($stuSql)->order("id DESC")->select();
                    }
                    $stuLists=array();
                    foreach ($stuList as $vo){
                        foreach ($vo as $v){
                            $stuLists[]=$v;
                        }
                    }
                    foreach ($stuLists as $k=>$v){
                        $staffSql['id']=$v['course_consultant'];
                        $staffSql['position']=3;
                        $course_consultant = D('staff')->where($staffSql)->find();
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
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['school']=array(
                    array('in',$schoolId)
                );
            }
        }
        /***获取管理员id,判断对应所属学校end***/
        if(isset($_POST['ids']) && $_GET["holiday"]){
            $ids = I('post.ids/a');
            $where['id']=array('in',$ids);
            if ( $this->class_model->where($where)->save(array('holiday'=>1,'vacation_time'=>time())) !== false ) {
                $this->success("放假成功！");
            } else {
                $this->error("放假失败！");
            }
        }
        if(isset($_POST['ids']) && $_GET["unholiday"]){
            $ids = I('post.ids/a');
            $where['id']=array('in',$ids);
            if ( $this->class_model->where($where)->save(array('holiday'=>0,'vacation_time'=>0)) !== false) {
                $this->success("取消放假成功！");
            } else {
                $this->error("取消放假失败！");
            }
        }
    }

//    //停课／取消停课
//    public function closed(){
//        if(isset($_POST['ids']) && $_GET["closed"]){
//            $ids = I('post.ids/a');
//            if ( $this->class_model->where(array('id'=>array('in',$ids)))->save(array('status'=>1,'closed_time'=>time())) !== false ) {
//                $this->success("停课成功！");
//            } else {
//                $this->error("停课失败！");
//            }
//        }
//        if(isset($_POST['ids']) && $_GET["unclosed"]){
//            $ids = I('post.ids/a');
//            if ( $this->class_model->where(array('id'=>array('in',$ids)))->save(array('status'=>0,'closed_time'=>0)) !== false) {
//                $this->success("取消停课成功！");
//            } else {
//                $this->error("取消停课失败！");
//            }
//        }
//    }

    // 学生管理列表
    public function student(){
        $classId=  I("get.classId",0,'intval');
        $where=array();
        $request=I('request.');
        $cid=0;
        if($classId>0){
            $where['ycs.class_id']=$classId;
            $cid=$classId;
        }elseif($request['classId']>0){
            $where['ycs.class_id']=$classId;
            $cid=$request['classId'];
        }
        if(!empty($request['surplus_hour'])){
            $where['ys.surplus_hour']=array('ELT',$request['ys.surplus_hour']);
        }
//        if(!empty($request['divide_class'])){
//            if($request['divide_class']==1){
//                $where['ys.class']=array('exp','is not null');
//            }elseif($request['divide_class']==2){
//                $where['class']=array('exp','is null');
//            }
//
//        }
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['ys.name']  = array('like', "%$keyword%");
//            $keyword_complex['class_name']  = array('like',"%$keyword%");
            $keyword_complex['ys.mobile']  = array('like',"%$keyword%");
            $keyword_complex['_logic'] = 'or';
            $where['_complex'] = $keyword_complex;
        }
        $start_time=strtotime(I('request.start_time'));
        if(!empty($start_time)){
            $where['ys.apply_date']=array(
                array('EGT',$start_time)
            );
        }

        $end_time=strtotime(I('request.end_time'));
        if(!empty($end_time)){
            if(empty($where['ys.apply_date'])){
                $where['ys.apply_date']=array();
            }
            array_push($where['ys.apply_date'], array('ELT',$end_time));
        }

        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['ys.school']=array(
                    array('in',$schoolId)
                );
                $staffSql['school_id']=array(
                    array('in',$schoolId)
                );
                $classSql['school']=array(
                    array('in',$schoolId)
                );
            }
        }
        /***获取管理员id,判断对应所属学校end***/
        $student_model=M("ClassStudent ycs");
        $count=$student_model
            ->join('left join yl_student_contract ysc on ysc.class=ycs.class_id')
            ->join('left join yl_students ys on ys.id=ysc.stu_id')
            ->where($where)->count();
        $page = $this->page($count, 20);
        $list = $student_model
            ->join('left join yl_student_contract ysc on ysc.class=ycs.class_id')
            ->join('left join yl_students ys on ys.id=ysc.stu_id')
            ->where($where)
            ->order("ys.add_time DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        foreach ($list as $k=>$v){
            $staffSql['id']=$v['course_consultant'];
            $staffSql['position']=3;
            $course_consultant = D('staff')->where($staffSql)->find();
            $list[$k]['course_consultant']=$course_consultant['name'];
            $list[$k]['apply_date']=date("Y-m-d",$v['apply_date']);
            $list[$k]['class_name']="";
            if(!empty($v['class'])){
                $classSql['id']=array("in",$v['class']);
                $classList=$this->class_model
                    ->where($classSql)
                    ->field("name")
                    ->select();
                $className=array();
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
                    $stuSql['school']=array(
                        array('in',$schoolId)
                    );
                    $contractSql['school']=array(
                        array('in',$schoolId)
                    );
                }
            }
            /***获取管理员id,判断对应所属学校end***/
            $class_consum=M("class_consum");

            $count=$class_consum->where($where)->count();
            $page = $this->page($count, 20);

            $list = $class_consum
                ->where($where)
                ->order("add_time DESC")
                ->limit($page->firstRow . ',' . $page->listRows)
                ->select();
            $classSql['id']=$classIds;
            $class=$this->class_model->where($classSql)->field('name,course,teacher')->find();
            foreach ($list as $k=>$v){
                $stuSql['id']=$v['stu_id'];
                $student=D('Students')->where($stuSql)->field('name')->find();
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
                $contractSql['card_info']=$v['card_info'];
                $contract=$this->studentContract_model->where($contractSql)->find();
                $totalPrice=$totalPrice+$v['class_hour']*$contract['price'];
                $allHour=$allHour+$v['class_hour'];
            }
            $this->assign('list', $list);
            $this->assign('totalPrice', $totalPrice);
            $this->assign('allHour', $allHour);
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

    //批量导出
    public function exportConsum(){
        if (IS_POST) {
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
                        $stuSql['school']=array(
                            array('in',$schoolId)
                        );
                        $contractSql['school']=array(
                            array('in',$schoolId)
                        );
                    }
                }
                /***获取管理员id,判断对应所属学校end***/

                $class_consum=M("class_consum");

                $count=$class_consum->where($where)->count();

                $list = $class_consum
                    ->where($where)
                    ->order("add_time DESC")
                    ->select();
                $classSql['id']=$classIds;
                $class=$this->class_model->where($classSql)->field('name,course,teacher')->find();
                foreach ($list as $k=>$v){
                    $stuSql['id']=$v['stu_id'];
                    $student=D('Students')->where($stuSql)->field('name')->find();
                    $list[$k]['add_time']=date("Y-m-d H:i:s",$v['add_time']);
                    $list[$k]['student_name']=$student['name'];
                    $list[$k]['class_name']=$class['name'];
                    $list[$k]['course']=$class['course'];
                    $contractSql['card_info']=$v['card_info'];
                    $contract=$this->studentContract_model->where($contractSql)->find();
                    $list[$k]['price']=$contract['price'];
                    $staffSql['status']=0;
                    $staffSql['position']=1;
                    $staffSql['id']=$class['teacher'];
                    $teacher = $this->staff_model->where($staffSql)->find();
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
}
