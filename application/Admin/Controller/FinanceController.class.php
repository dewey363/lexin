<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Admin\Model\FinanceModel;

class FinanceController extends AdminbaseController{
    protected $finance_model;
    protected $studentContract_model;
    public function _initialize() {
        parent::_initialize();
        $this->finance_model = D("Admin/Finance");
        $this->studentContract_model = D("Admin/StudentContract");
    }
    
    //费用管理列表
    public function index(){
        $where=array();
        $request=I('request.');
        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $where['name'] = array('like', "%$keyword%");
        }
        if($request['type']>0){
            $where['type'] =$request['type'];
        }
        if($request['source']>0){
            $where['source'] =$request['source'];
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
        }
    	$this->assign('list', $list);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }

    //费用统计
    public function total(){
        $where=array();
        $start=I('request.start_time');

        if(empty($start)){
            $start=date('Y-m-01');
        }
        $start_time=strtotime($start);

        $where['create_time']=array(
            array('EGT',$start_time)
        );

        $end=I('request.end_time');

        if(empty($end)){
            $end=date('Y-m-d');
        }
        $end_time=strtotime($end);

        if(empty($where['create_time'])){
            $where['create_time']=array();
        }
        array_push($where['create_time'], array('ELT',$end_time));

        //课时消耗查询
        $class_consum=M("class_consum");
        $consum = $class_consum
            ->where($where)
            ->select();
        $consum_sum=0;
        foreach ($consum as $v){
            $contract = $this->studentContract_model->where(array("id"=>$v['contract']))->find();
            $price=$v['class_hour']*$contract['price'];
            $consum_sum=$consum_sum+$price;
        }
        $charge_income=0;
        $charge_expend=0;
        $refund_income=0;
        $refund_expend=0;
        $market_income=0;
        $market_expend=0;
        $personnel_income=0;
        $personnel_expend=0;
        $other_income=0;
        $other_expend=0;
        //学员收费
        $list = $this->finance_model->where($where)->where(array("status"=>1,"is_del"=>0))->select();
        foreach ($list as $v){
            if($v['type']==1){
                if($v['source']==1){
                    $charge_income=$charge_income+$v['price'];
                }elseif($v['source']==2){
                    $charge_expend=$charge_expend+$v['price'];
                }
            }elseif($v['type']==2){
                if($v['source']==1){
                    $refund_income=$refund_income+$v['price'];
                }elseif($v['source']==2){
                    $refund_expend=$refund_expend+$v['price'];
                }

            }elseif($v['type']==3){
                if($v['source']==1){
                    $market_income=$market_income+$v['price'];
                }elseif($v['source']==2){
                    $market_expend=$market_expend+$v['price'];
                }

            }elseif($v['type']==4){
                if($v['source']==1){
                    $personnel_income=$personnel_income+$v['price'];
                }elseif($v['source']==2){
                    $personnel_expend=$personnel_expend+$v['price'];
                }
            }elseif($v['type']==5){
                if($v['source']==1){
                    $other_income=$other_income+$v['price'];
                }elseif($v['source']==2){
                    $other_expend=$other_expend+$v['price'];
                }
            }
        }
        $list['consum_sum']=$consum_sum;
        $list['charge_income']=$charge_income;
        $list['charge_expend']=$charge_expend;
        $list['refund_income']=$refund_income;
        $list['refund_expend']=$refund_expend;
        $list['market_income']=$market_income;
        $list['market_expend']=$market_expend;
        $list['personnel_income']=$personnel_income;
        $list['personnel_expend']=$personnel_expend;
        $list['other_income']=$other_income;
        $list['other_expend']=$other_expend;

        $this->assign('list', $list);
        $this->assign('start_time', $start);
        $this->assign('end_time', $end);
        $this->display();
    }
    //新增学员合同
    public function add(){
        $type=FinanceModel::getType();
        $payType=FinanceModel::getPayType();
        $this->assign('type', $type);
        $this->assign('payType', $payType);
        $this->display();
    }

    //新增学员合同提交
    public function add_post(){
        if (IS_POST) {
            $finance['type']=I('type',0);
            $finance['source']=I('source',0);
            $finance['price']=I('price',"0");
            $finance['pay_type']=I('pay_type',0);
            $finance['status']=I('status',0);
            $finance['project']=I('project',"");
            $finance['note']=I('note',"");
//          $finance['user_id']=I('user_id',0);
//          $finance['contract_id']=I('contract_id',0);
            //学校id
//          $finance['school']=I('school',0);
            $finance['add_time']=strtotime(I('add_time'));
            $finance['create_time']=time();
            $finance['update_time']=time();
            $uid=sp_get_current_admin_id();
            $finance['admin_id']=$uid;
            $result=$this->finance_model->add($finance);
            if ($result) {
                $this->success("添加成功！",U("finance/index"));
            } else {
                $this->error("添加失败！");
            }

        }
    }

    //学员合同编辑
    public function financeEdit(){
        $id=  I("get.id",0,'intval');
        $info=$this->finance_model->where("id=$id")->find();
        $info['add_time']=date("Y-m-d",$info['add_time']);
        $info['price']=round($info['price'],2);
        $type=FinanceModel::getType();
        $payType=FinanceModel::getPayType();
        foreach ($type as $k=>$v){
            if($v['id']==$info['type']){
                $type[$k]['selected']="selected";
            }else{
                $type[$k]['selected']="";
            }
        }
        foreach ($payType as $k=>$v){
            if($v['id']==$info['pay_type']){
                $payType[$k]['selected']="selected";
            }else{
                $payType[$k]['selected']="";
            }
        }
        $this->assign('type', $type);
        $this->assign('payType', $payType);
        $this->assign("info",$info);
        $this->assign("id",$id);
        $this->display();
    }
    //学员合同编辑
    public function edit(){
        $id=  I("get.id",0,'intval');
        $info=$this->finance_model->where("id=$id")->find();
        $info['add_time']=date("Y-m-d",$info['add_time']);
        $info['price']=round($info['price'],2);
        $type=FinanceModel::getType();
        $payType=FinanceModel::getPayType();
        foreach ($type as $k=>$v){
            if($v['id']==$info['type']){
                $type[$k]['selected']="selected";
            }else{
                $type[$k]['selected']="";
            }
        }
        foreach ($payType as $k=>$v){
            if($v['id']==$info['pay_type']){
                $payType[$k]['selected']="selected";
            }else{
                $payType[$k]['selected']="";
            }
        }
        $this->assign('type', $type);
        $this->assign('payType', $payType);
        $this->assign("info",$info);
        $this->assign("id",$id);
        $this->display();
    }

    //学员合同编辑提交
    public function edit_post(){
        if (IS_POST) {
            $finance['id']=intval($_POST['id']);
            $info=$this->finance_model ->where(array("id"=>$finance['id']))->find();

            if(!empty($info)){
                $finance['type']=I('type',$info['type']);
                $finance['source']=I('source',$info['source']);
                $finance['price']=I('price',$info['price']);
                $finance['pay_type']=I('pay_type',$info['pay_type']);
                $finance['status']=I('status',$info['status']);
                $finance['project']=I('project',$info['project']);
                $finance['note']=I('note',$info['note']);
                //            $finance['user_id']=I('user_id',0);
                //            $finance['contract_id']=I('contract_id',0);
                //学校id
//          $finance['school']=I('school',0);
                $finance['add_time']=strtotime(I('add_time'));
                $finance['update_time']=time();
                $uid=sp_get_current_admin_id();
                $finance['update_admin']=$uid;
                $result=$this->finance_model ->save($finance);
                if ($result!==false) {
                    if($info['contract_id']>0 && $finance['status']==1){
                        $contracts['status']=2;
                        $contracts['id']=$info['contract_id'];
                        $this->studentContract_model->save($contracts);
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
        $finance['id']=$id;
        $finance['is_del']=1;
        $result=$this->finance_model->save($finance);
        if ($result) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    //批量导出学员合同信息
    public function export(){
        $where=array();
        $request=I('request.');
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
        $list = $this->finance_model->where($where)
            ->order("create_time DESC")
            ->select();
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
        }
        FinanceModel::exportList($list);
        exit;
    }
}
