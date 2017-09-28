<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Admin\Model\StudentContractModel;
use Admin\Model\StudentModel;

class ContractController extends AdminbaseController{
    protected $studentContract_model;
    protected $finance_model;
    protected $class_model;

    public function _initialize() {
        parent::_initialize();
        $this->studentContract_model = D("Admin/StudentContract");
        $this->finance_model = D("Admin/Finance");
        $this->class_model = D("Admin/Class");
    }
    
    //合同管理列表
    public function index(){
        $stuId=  I("get.stuId",0,'intval');
        $where=array();
        if($stuId>0){
            $where['stu_id']=$stuId;
        }
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $where['name'] = array('like', "%$keyword%");
        }

        if(!empty($request['surplus_hour'])){
            $where['surplus_hour']=array('ELT',$request['surplus_hour']);
        }

        if(!empty($request['divide_class'])){
            $sql['is_del']=0;
            $contractList=D('ClassStudent')->where($sql)->field('contract_id')->select();
            $contractIds='';
            if(!empty($contractList)){
                $contractId=array();
                foreach ($contractList as $v){
                    $contractId[]=$v['contract_id'];
                }
                if(!empty($contractId)){
                    $contractIds=implode(',',$contractId);
                }
            }
            if(!empty($contractIds)) {
                if ($request['divide_class'] == 1) {
                    $where['id'] = array(
                        array('in', $contractIds)
                    );
                } elseif ($request['divide_class'] == 2) {
                    $where['id'] = array(
                        array('not in', $contractIds)
                    );
                }
            }
        }

        $start_time=strtotime(I('request.start_time'));
        if(!empty($start_time)){
            $where['create_time']=array(
                array('EGT',$start_time)
            );
        }

        $end_time=strtotime(I('request.end_time'));
        if(!empty($end_time)){
            if(empty($where['create_time'])){
                $where['create_time']=array();
            }
            array_push($where['create_time'], array('ELT',$end_time));
        }
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        $classSql1=array();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['school']=array(
                    array('in',$schoolId)
                );
                $classSql['school']=array(
                    array('in',$schoolId)
                );
                $classSql1['school']=array(
                    array('in',$schoolId)
                );
                $stuSql['school']=array(
                    array('in',$schoolId)
                );
            }
        }
        /***获取管理员id,判断对应所属学校end***/
    	$where['is_del']=0;
    	$count=$this->studentContract_model ->where($where)->count();
    	$page = $this->page($count, 20);
    	
    	$list = $this->studentContract_model->where($where)
    	->order("create_time DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	foreach ($list as $k=>$v){
    	    $list[$k]['start_time']=date("Y-m-d",$v['start_time']);
    	    $list[$k]['end_time']=date("Y-m-d",$v['end_time']);
            $stuSql['status']=0;
            $stuSql['id']=$v['stu_id'];
            $studentInfo=D('Students')->where($stuSql)->find();
            $list[$k]['stu_name']=$studentInfo['name'];
            $userSql['user_status']=1;
            $userSql['user_type']=1;
            $userSql['id']=$v['admin_id'];
            $adminInfo=D('Users')->where($userSql)->find();
            $list[$k]['admin_name']=$adminInfo['user_login'];
            $list[$k]['price']=round($v['price'],2);
            $list[$k]['total_price']=round($v['total_price'],2);

            if($v['status']==0){
                $list[$k]['status']="待审核";
            }elseif($v['status']==1){
                $list[$k]['status']="已结束";
            }elseif($v['status']==2){
                if($v['start_time'] > time()){
                    $list[$k]['status']="未开始";
                }
                if($v['start_time'] <= time() && $v['end_time'] > time()){
                    $list[$k]['status']="进行中";
                }
            }
            //查询班级信息
            $arr['is_del']=0;
            $arr['contract_id']=$v['id'];
            $classId=D('ClassStudent')->where($arr)->field('class_id')->find();
            if(!empty($classId)){
                $classSql['is_del']=0;
                $classSql['id']=$classId['class_id'];
                $classInfo=$this->class_model->where($classSql)->field('name')->find();
                $list[$k]['class_name']=$classInfo['name'];
            }else{
                $list[$k]['class_name']='暂无班级';
            }

        }

        $classList=$this->class_model->where($classSql1)->select();
        $this->assign("classList",$classList);
    	$this->assign('list', $list);
    	$this->assign('stuId', $stuId);
    	$this->assign("page", $page->show('Admin'));
    	$this->display();
    }

    //新增学员合同
    public function add(){
        $stuId=  I("get.stuId",0,'intval');
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $stuSql['school']=array(
                    array('in',$schoolId)
                );
            }
        }
        /***获取管理员id,判断对应所属学校end***/
        $stuSql['status']=0;
        $studentList=D('Students')->where($stuSql)->select();
        foreach ($studentList as $k=>$v){
            if($v['id']==$stuId){
                $studentList[$k]['selected']="selected";
            }else{
                $studentList[$k]['selected']="";
            }
        }

        $this->assign('stuId', $stuId);
        $this->assign('studentList', $studentList);
        $this->display();
    }

    //新增学员合同提交
    public function add_post(){
        if (IS_POST) {
            $post=I('post.');
            $validate=StudentContractModel::validate($post);
            if(!empty($validate)){
                $this->error($validate);
            }
            /***获取管理员id,判断对应所属学校start***/
            $adminId=sp_get_current_admin_id();
            if($adminId !=1){
                $schoolId=get_current_school();
                if(!empty($schoolId)){
                    $stuSql['school']=array(
                        array('in',$schoolId)
                    );
                    $contractSql['school']=array(
                        array('in',$schoolId)
                    );
                }
            }
            /***获取管理员id,判断对应所属学校end***/
            $contracts['card_info']=trim(I('card_info'));
            $contractSql['status']=0;
            $contractSql['card_info']=$contracts['card_info'];
            $conInfo=D('student_contract')->where($contractSql)->find();
            if(!empty($conInfo)){
                $this->error("该卡号已绑定有效合同！");
            }
            $contracts['stu_id']=I('stu_id',0);
            $stuSql['id']=$contracts['stu_id'];
            $stuInfo=D('Students')->where($stuSql)->field('school')->find();
            $contracts['school']=$stuInfo['school'];
            $contracts['name']=I('name',"");
            $contracts['total_price']=I('total_price',"0");
            $contracts['class_number']=I('class_number',"");
            $contracts['price']=$contracts['total_price']/$contracts['class_number'];
            $contracts['start_time']=strtotime(I('start_time'));
            $contracts['end_time']=strtotime(I('end_time'));
            $contracts['create_time']=time();
            $contracts['update_time']=time();
            $uid=sp_get_current_admin_id();
            $contracts['admin_id']=$uid;
            $contracts['class']=I('class',0);
            $contracts['course']=I('course');
            $contracts['time_consuming_reminder']=I('time_consuming_reminder');
            $contracts['hire_purchase']=I('hire_purchase',0);


            $result=$this->studentContract_model->add($contracts);
            if ($result) {
                $finance['type']=1;
                $finance['source']=1;
                $finance['price']=$contracts['total_price'];
                $finance['project']=$contracts['name'];
                $finance['user_id']=$contracts['stu_id'];
                $finance['contract_id']=$result;
                $uid=sp_get_current_admin_id();
                $finance['admin_id']=$uid;
                $finance['add_time']=time();
                $finance['create_time']=time();
                $finance['update_time']=time();
                $finance['school']=$contracts['school'];
                $this->finance_model->add($finance);
                $this->success("添加成功！",U("contract/index"));
            } else {
                $this->error("添加失败！");
            }

        }
    }

    //学员合同编辑
    public function edit(){
        $id=  I("get.id",0,'intval');
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
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
        $contractSql['id']=$id;
        $info=$this->studentContract_model->where($contractSql)->find();
        $info['start_time']=date("Y-m-d",$info['start_time']);
        $info['end_time']=date("Y-m-d",$info['end_time']);
        $info['total_price']=round($info['total_price'],2);
        $stuSql['status']=0;
        $studentList=D('Students')->where($stuSql)->select();
        foreach ($studentList as $k=>$v){
            if($v['id']==$info['stu_id']){
                $studentList[$k]['selected']="selected";
            }else{
                $studentList[$k]['selected']="";
            }
        }
        $info['consume_hour']=empty($info['consume_hour'])?0:$info['consume_hour'];
        $info['surplus_hour']=$info['class_number']-$info['consume_hour'];
        $info['refundPrice']=($info['total_price']/$info['class_number'])*$info['surplus_hour'];

        $where['is_del']=0;
        $where['contract_id']=$id;
        $classId=D('ClassStudent')->where($where)->field('class_id')->find();
        if(!empty($classId)){
            $classSql['is_del']=0;
            $classSql['id']=$classId['class_id'];
            $classInfo=$this->class_model->where($classSql)->field('name')->find();
            $info['class_name']=$classInfo['name'];
        }else{
            $info['class_name']='暂无班级';
        }

        $this->assign("info",$info);
        $this->assign("studentList",$studentList);
        $this->assign("id",$id);
        $this->display();
    }
//学员合同查看
    public function view(){
        $id=  I("get.id",0,'intval');
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['school']=array(
                    array('in',$schoolId)
                );
                $classSql['school']=array(
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
            }
        }
        /***获取管理员id,判断对应所属学校end***/
        $contractSql['id']=$id;
        $info=$this->studentContract_model->where($contractSql)->find();
        $info['start_time']=date("Y-m-d",$info['start_time']);
        $info['end_time']=date("Y-m-d",$info['end_time']);
        $info['total_price']=round($info['total_price'],2);
        $stuSql['status']=0;
        $stuSql['id']=$info['stu_id'];
        $studentInfo=D('Students')->where($stuSql)->find();
        $info['stu_name']=$studentInfo['name'];
        $info['consume_hour']=empty($info['consume_hour']) ? 0:$info['consume_hour'];
        $info['surplus_hour']=$info['class_number']-$info['consume_hour'];
        $info['refundPrice']=($info['total_price']/$info['class_number'])*$info['surplus_hour'];
        $info['unit_price']=$info['total_price']/$info['class_number'];
        $classSql['id']=$info['class'];
        $classInfo=$this->class_model->where($classSql)->find();
        $info['class_name']=$classInfo['name'];
        $info['hire_purchase']=isset($info['hire_purchase']) && $info['hire_purchase']==1 ? "是":"否";
        $info['class_end']=isset($info['class_end']) && $info['class_end']==1 ? "是":"否";
        //获取费用记录
        $where=array();
        $request=I('request.');
        $where['user_id']=$info['stu_id'];
        $where['contract_id']=$id;
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $where['name'] = array('like', "%$keyword%");
        }
        if(!empty($request['type'])){
            $where['type'] =$request['type'];
        }
        $start_time=strtotime(I('request.start_time'));
        if(!empty($start_time)){
            $where['create_time']=array(
                array('EGT',$start_time)
            );
        }

        $end_time=strtotime(I('request.end_time'));
        if(!empty($end_time)){
            if(empty($where['create_time'])){
                $where['create_time']=array();
            }
            array_push($where['create_time'], array('ELT',$end_time));
        }

        $where['is_del']=0;
        $count=$this->finance_model ->where($where)->count();
        $page = $this->page($count, 20);

        $list = $this->finance_model->where($where)
            ->order("create_time DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        $loginUid=sp_get_current_admin_id();
        foreach ($list as $k=>$v){
            $list[$k]['update_time']=date("Y-m-d H:i:s",$v['update_time']);
            if($v['user_id']>0){
                $stuSql1['status']=0;
                $stuSql1['id']=$v['user_id'];
                $studentInfo=D('Students')->where($stuSql1)->find();
                $list[$k]['stu_name']=$studentInfo['name'];
            }
            //创建者
            $adminInfo=D('Users')->where(array("user_status"=>1,"user_type"=>1,"id"=>$v['admin_id']))->find();
            $list[$k]['admin_name']=$adminInfo['user_login'];
            //更新者
            $adminInfo=D('Users')->where(array("user_status"=>1,"user_type"=>1,"id"=>$v['update_admin']))->find();
            $list[$k]['update_admin_name']=$adminInfo['user_login'];
            $list[$k]['price']=round($v['price'],2);
            if($v['source']==1){
                $list[$k]['source']='收入';
            }elseif($v['source']==2){
                $list[$k]['source']='支出';
            }
            $type=StudentContractModel::getType();
            $payType=StudentContractModel::getPayType();
            foreach ($type as $key=>$value){
                $list[$k]['type']=$type[$v['type']]['name'];
            }
            foreach ($payType as $key=>$value){
                $list[$k]['payType']=$type[$v['pay_type']]['name'];
            }
            if($v['status']==0){
                $list[$k]['statusName']='待审核';
            }elseif($v['status']==1){
                $list[$k]['statusName']='审核通过';
            }elseif($v['status']==2){
                $list[$k]['statusName']='打回';
            }

            if($loginUid==$v['admin_id'] && $v['status']==2){
                $list[$k]['edit_ok']=1;
            }else{
                $list[$k]['edit_ok']=0;
            }
        }
        $this->assign('loginUid', $loginUid);
        $this->assign('list', $list);
        $this->assign("page", $page->show('Admin'));
        //费用记录结束

        $this->assign("info",$info);
        $this->assign("id",$id);
        $this->display();
    }
    //学员合同编辑提交
    public function edit_post(){
        if (IS_POST) {
            $contracts['id']=intval($_POST['id']);
            $info=$this->studentContract_model ->where(array("id"=>$contracts['id']))->find();

            if(!empty($info)){
                $post=I('post.');
                $post['total_price']=$info['total_price'];
                $post['class_number']=$info['class_number'];
                $validate=StudentContractModel::validate($post);
                if(!empty($validate)){
                    $this->error($validate);
                }
                $contracts['stu_id']=I('stu_id',$info['stu_id']);
                if(empty($info['school'])){
                    $stuInfo=D('Students')->where(array('id'=>$contracts['stu_id']))->field('school')->find();
                    $contracts['school']=$stuInfo['school'];
                }else{
                    $contracts['school']=$info['school'];
                }
                $contracts['name']=I('name',$info['name']);
                $contracts['total_price']=$info['total_price']+I('renewal');
                $contracts['class_number']=$info['class_number']+I('continue_class');
                $contracts['price']=$contracts['total_price']/$contracts['class_number'];

                if(strtotime(I('end_time')) < $info['end_time']){
                    $this->error("合同结束时间不能小于原有结束时间！");
                }
                $contracts['start_time']=strtotime(I('start_time'));
                $contracts['end_time']=strtotime(I('end_time'));
                $contracts['update_time']=time();
                $uid=sp_get_current_admin_id();
                $contracts['update_admin']=$uid;

                $contracts['class']=I('class',$info['class_end']);
                $contracts['class_end']=I('class_end',$info['class_end']);
                $contracts['hire_purchase']=I('hire_purchase',$info['hire_purchase']);
                $contracts['time_consuming_reminder']=I('time_consuming_reminder',$info['time_consuming_reminder']);
                $contracts['card_info']=trim(I('card_info',$info['card_info']));
                $conInfo=D('student_contract')->where(array('status'=>0,'card_info'=>$contracts['card_info']))->find();
                if(!empty($conInfo) && $conInfo['id'] !=$contracts['id']){
                    $this->error("该卡号已绑定有效合同！");
                }
                $contracts['course']=I('course',$info['course']);


                $result=$this->studentContract_model ->save($contracts);
                if ($result!==false) {
                    if(!empty(I('renewal'))){
                        $finance['type']=1;
                        $finance['source']=1;
                        $finance['price']=I('renewal');
                        $finance['project']=$contracts['name'];
                        $finance['user_id']=$contracts['stu_id'];
                        $finance['contract_id']=$result;
                        $uid=sp_get_current_admin_id();
                        $finance['admin_id']=$uid;
                        $finance['add_time']=time();
                        $finance['create_time']=time();
                        $finance['update_time']=time();
                        $finance['school']=$contracts['school'];
                        $this->finance_model->add($finance);
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
        $contracts['id']=$id;
        $contracts['is_del']=1;
        $contract_model=M("student_contract");
        $result=$contract_model->save($contracts);
        if ($result) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    //批量导出学员合同信息
    public function export(){
        $stuId=  I("get.stuId",0,'intval');
        $where=array();
        if($stuId>0){
            $where['stu_id']=$stuId;
        }
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $where['name'] = array('like', "%$keyword%");
        }
        $start_time=strtotime(I('request.start_time'));
        if(!empty($start_time)){
            $where['create_time']=array(
                array('EGT',$start_time)
            );
        }

        $end_time=strtotime(I('request.end_time'));
        if(!empty($end_time)){
            if(empty($where['create_time'])){
                $where['create_time']=array();
            }
            array_push($where['create_time'], array('ELT',$end_time));
        }
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        $classSql1=array();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['school']=array(
                    array('in',$schoolId)
                );
                $classSql['school']=array(
                    array('in',$schoolId)
                );
                $classSql1['school']=array(
                    array('in',$schoolId)
                );
                $stuSql['school']=array(
                    array('in',$schoolId)
                );
            }
        }
        /***获取管理员id,判断对应所属学校end***/
        $where['is_del']=0;
        $count=$this->studentContract_model ->where($where)->count();
        $page = $this->page($count, 20);

        $list = $this->studentContract_model->where($where)
            ->order("create_time DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        foreach ($list as $k=>$v){
            $list[$k]['start_time']=date("Y-m-d",$v['start_time']);
            $list[$k]['end_time']=date("Y-m-d",$v['end_time']);
            $userSql['status']=0;
            $userSql['id']=$v['stu_id'];
            $studentInfo=D('Students')->where($userSql)->find();
            $list[$k]['stu_name']=$studentInfo['name'];
            $adminInfo=D('Users')->where(array("user_status"=>1,"user_type"=>1,"id"=>$v['admin_id']))->find();
            $list[$k]['admin_name']=$adminInfo['user_login'];
            $list[$k]['price']=round($v['total_price']/$v['class_number'],2);
            $list[$k]['total_price']=round($v['total_price'],2);

            if($v['status']==0){
                $list[$k]['status']="待审核";
            }elseif($v['status']==1){
                $list[$k]['status']="已结束";
            }elseif($v['status']==2){
                if($v['start_time'] > time()){
                    $list[$k]['status']="未开始";
                }
                if($v['start_time'] <= time() && $v['end_time'] > time()){
                    $list[$k]['status']="进行中";
                }
            }
        }
        StudentContractModel::exportList($list);
        exit;
    }

    //退费
    public function refund(){
        if (IS_POST) {
            $id=  I('id');
            $info=$this->studentContract_model->where("id=$id")->find();
            $refundTitle=I('refundTitle');
            $refundPrice=I('refundPrice');
            $uid=sp_get_current_admin_id();
            $price=0;
            if(!empty($refundTitle)){
                $dataList=array();
                foreach ($refundTitle as $k=>$v){
                    $dataList[] = array(
                        'type'=>2,
                        'source'=>1,
                        'user_id'=>$info['stu_id'],
                        'contract_id'=>$id,
                        'project'=>$v,
                        'price'=>$refundPrice[$k],
                        'admin_id'=>$uid,
                        'add_time'=>time(),
                        'create_time'=>time(),
                        'update_time'=>time()
                    );
                    $price=$price+$refundPrice[$k];
                }
                $surplus_hour=$info['class_number']-$info['consume_hour'];
                $refundPrice=($info['total_price']/$info['class_number'])*$surplus_hour;
                $refund=$refundPrice-$price;
                $dataLists[]= array(
                    'type'=>2,
                    'source'=>2,
                    'user_id'=>$info['stu_id'],
                    'contract_id'=>$id,
                    'project'=>"退费",
                    'price'=>$refund,
                    'admin_id'=>$uid,
                    'add_time'=>time(),
                    'create_time'=>time(),
                    'update_time'=>time()
                );
                $array=array_merge($dataList,$dataLists);
                if(!empty($dataList)){
                    $add=$this->finance_model->addAll($array);
                    if($add){
                        $contracts['id']=$id;
                        $contracts['update_time']=time();
                        $contracts['status']=1;
                        $uid=sp_get_current_admin_id();
                        $contracts['update_admin']=$uid;
                        $this->studentContract_model ->save($contracts);

//                        $this->success("操作成功，退款金额为：！",U("contract/edit",array("id"=>$id)));
                        $this->success("操作成功，退款金额为：".$refund."!");
                    }else{
                        $this->error("添加失败！");
                    }
                }
            }
        }
    }

    // 停课／开课
    public function suspend(){
        $student_model=M("student_contract");
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['school_id']=array(
                    array('in',$schoolId)
                );
            }
        }

        /***获取管理员id,判断对应所属学校end***/
        if(isset($_POST['ids']) && $_GET["suspend"]){
            $ids = I('post.ids/a');
            $where['id']=array('in',$ids);
            if ( $student_model->where($where)->save(array('class_end'=>1)) !== false ) {
                $this->success("停课成功！");
            } else {
                $this->error("停课失败！");
            }
        }
        if(isset($_POST['ids']) && $_GET["unsuspend"]){
            $ids = I('post.ids/a');
            $where['id']=array('in',$ids);
            if ( $student_model->where($where)->save(array('class_end'=>0)) !== false) {
                $this->success("取消停课成功！");
            } else {
                $this->error("取消停课失败！");
            }
        }
    }

    //转班
    public function returnClass(){
        $ids = I('post.ids');
        $classId = I('post.class_id');
        if(!empty($ids) && $classId >0){
            $classStudent=M('ClassStudent');
            //提交的合同id转数组
            $idArr=explode(',',$ids);
            $oldConId=array();
            foreach ($idArr as $k=>$v){
                $csInfo=$classStudent->where(array("contract_id"=>$v,"is_del"=>0))->select();
                foreach ($csInfo as $vo){
                    if($vo['class_id']==$classId){
                        unset($idArr[$k]);
                    }else{
                        $oldConId[]=$v;
                    }
                }
            }
            $oldConIdStr=implode(',',$oldConId);
            //原有分班数据软删除
            $csWhere['contract_id']=array('in',$oldConIdStr);
            $csWhere['is_del']=0;
            $csArr['is_del']=1;
            $classStudent->where($csWhere)->save($csArr);

            //添加分班数据关联
            foreach($idArr as $v){
                $oldCsInfo=$classStudent->where(array("contract_id"=>$v,"class_id"=>$classId,"is_del"=>1))->find();
                if(!empty($oldCsInfo)){
                    $data['contract_id']=$v;
                    $data['class_id']=$classId;
                    $dataS['is_del']=0;
                    $classStudent->where($data)->save($dataS);
                }else{
                    $data['contract_id']=$v;
                    $data['class_id']=$classId;
                    $classStudent->add($data);
                }
                $conWhere['id']=$v;
                $contract['class']=$classId;
                $this->studentContract_model->where($conWhere)->save($contract);
            }
            $return = ['code' => 1, 'msg' => '转班成功'];
            $this->ajaxReturn($return);
        }
    }

    public function consumAdd()
    {
        $cardNum=  I("get.cardNum");
        if(!$cardNum){
            $this->error("请填写卡号！");
        }
        //定义一个要发送的目标URL；
        $url = "http://111.231.63.219:6017/api/v1/class/consum/add";
        //定义传递的参数数组；
        $data['cardNum']=$cardNum;
        //定义返回值接收变量；
        $result=StudentModel::http($url, $data);
        $msg=json_decode($result,true);
        if($msg['code'] !=1001){
            $this->error($msg['message']);
        }else{
            $this->success($msg['message']);
        }

    }
}
