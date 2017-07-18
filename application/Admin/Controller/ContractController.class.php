<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Admin\Model\StudentContractModel;
use Admin\Model\FinanceModel;

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
    	$count=$this->studentContract_model ->where($where)->count();
    	$page = $this->page($count, 20);
    	
    	$list = $this->studentContract_model->where($where)
    	->order("create_time DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	foreach ($list as $k=>$v){
    	    $list[$k]['start_time']=date("Y-m-d",$v['start_time']);
    	    $list[$k]['end_time']=date("Y-m-d",$v['end_time']);
            $studentInfo=D('Students')->where(array("status"=>0,'id'=>$v['stu_id']))->find();
            $list[$k]['stu_name']=$studentInfo['name'];
            $adminInfo=D('Users')->where(array("user_status"=>1,"user_type"=>1,"id"=>$v['admin_id']))->find();
            $list[$k]['admin_name']=$adminInfo['user_login'];
            $list[$k]['price']=round($v['total_price']/$v['class_number'],2);
            $list[$k]['total_price']=round($v['total_price'],2);
            if($v['start_time'] > time()){
                $list[$k]['status']="未开始";
            }
            if($v['start_time'] <= time() && $v['end_time'] > time()){
                $list[$k]['status']="进行中";
            }
            if($v['status']==1){
                $list[$k]['status']="已结束";
            }
        }
    	$this->assign('list', $list);
    	$this->assign('stuId', $stuId);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }

    //新增学员合同
    public function add(){
        $stuId=  I("get.stuId",0,'intval');
        $studentList=D('Students')->where(array("status"=>0))->select();
        foreach ($studentList as $k=>$v){
            if($v['id']==$stuId){
                $studentList[$k]['selected']="selected";
            }else{
                $studentList[$k]['selected']="";
            }
        }
        $classList=$this->class_model->select();
        $this->assign("classList",$classList);
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
            $contracts['card_info']=trim(I('card_info'));
            $conInfo=D('student_contract')->where(array('status'=>0,'card_info'=>$contracts['card_info']))->find();
            if(!empty($conInfo)){
                $this->error("该卡号已绑定有效合同！");
            }
            $contracts['stu_id']=I('stu_id',0);
            $contracts['name']=I('name',"");
            $contracts['total_price']=I('total_price',"0");
            $contracts['class_number']=I('class_number',"");
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
        $info=$this->studentContract_model->where("id=$id")->find();
        $info['start_time']=date("Y-m-d",$info['start_time']);
        $info['end_time']=date("Y-m-d",$info['end_time']);
        $info['total_price']=round($info['total_price'],2);

        $studentList=D('Students')->where(array("status"=>0))->select();
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
        $classList=$this->class_model->select();
        foreach ($classList as $k=>$v){
            if($v['id']==$info['class']){
                $classList[$k]['selected']="selected";
            }else{
                $classList[$k]['selected']="";
            }
        }
        $this->assign("classList",$classList);
        $this->assign("info",$info);
        $this->assign("studentList",$studentList);
        $this->assign("id",$id);
        $this->display();
    }
//学员合同查看
    public function view(){
        $id=  I("get.id",0,'intval');
        $info=$this->studentContract_model->where("id=$id")->find();
        $info['start_time']=date("Y-m-d",$info['start_time']);
        $info['end_time']=date("Y-m-d",$info['end_time']);
        $info['total_price']=round($info['total_price'],2);

        $studentInfo=D('Students')->where(array("status"=>0,'id'=>$info['stu_id']))->find();
        $info['stu_name']=$studentInfo['name'];
        $info['consume_hour']=empty($info['consume_hour']) ? 0:$info['consume_hour'];
        $info['surplus_hour']=$info['class_number']-$info['consume_hour'];
        $info['refundPrice']=($info['total_price']/$info['class_number'])*$info['surplus_hour'];
        $info['unit_price']=$info['total_price']/$info['class_number'];
        $classInfo=$this->class_model->where(array('id'=>$info['class']))->find();
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
                $studentInfo=D('Students')->where(array("status"=>0))->find();
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
            $type=FinanceModel::getType();
            $payType=FinanceModel::getPayType();
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
                $contracts['name']=I('name',$info['name']);
                if(!empty(I('renewal'))){
                    $contracts['total_price']=$info['total_price']+I('renewal');
                }
                if(!empty(I('renewal'))){
                    $contracts['class_number']=$info['class_number']+I('continue_class');
                }
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

        $where['is_del']=0;

        $list = $this->studentContract_model->where($where)
            ->order("create_time DESC")
            ->select();
        foreach ($list as $k=>$v){
            $list[$k]['start_time']=date("Y-m-d",$v['start_time']);
            $list[$k]['end_time']=date("Y-m-d",$v['end_time']);
            $studentInfo=D('Students')->where(array("status"=>0))->find();
            $list[$k]['stu_name']=$studentInfo['name'];
            $adminInfo=D('Users')->where(array("user_status"=>1,"user_type"=>1,"id"=>$v['admin_id']))->find();
            $list[$k]['admin_name']=$adminInfo['user_login'];
            $list[$k]['price']=round($v['total_price']/$v['class_number'],2);
            $list[$k]['total_price']=round($v['total_price'],2);
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
                $dataList=[];
                foreach ($refundTitle as $k=>$v){
                    $dataList[] = array(
                        'type'=>2,
                        'source'=>2,
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
                if(!empty($dataList)){
                    $add=$this->finance_model->addAll($dataList);
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
        if(isset($_POST['ids']) && $_GET["suspend"]){
            $ids = I('post.ids/a');

            if ( $student_model->where(array('id'=>array('in',$ids)))->save(array('class_end'=>1)) !== false ) {
                $this->success("停课成功！");
            } else {
                $this->error("停课失败！");
            }
        }
        if(isset($_POST['ids']) && $_GET["unsuspend"]){
            $ids = I('post.ids/a');

            if ( $student_model->where(array('id'=>array('in',$ids)))->save(array('class_end'=>0)) !== false) {
                $this->success("取消停课成功！");
            } else {
                $this->error("取消停课失败！");
            }
        }
    }
}
