<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class ContractController extends AdminbaseController{
    protected $studentContract_model;
    public function _initialize() {
        parent::_initialize();
        $this->studentContract_model = D("Admin/StudentContract");
    }
    
    //学生管理列表
    public function index(){
        $where=array();
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
            $studentInfo=D('Students')->where(array("status"=>0))->find();
            $list[$k]['stu_name']=$studentInfo['name'];
            $adminInfo=D('Users')->where(array("user_status"=>1,"user_type"=>1,"id"=>$v['admin_id']))->find();
            $list[$k]['admin_name']=$adminInfo['user_login'];
            $list[$k]['price']=$v['total_price']/$v['class_number'];
            $list[$k]['total_price']=round($v['total_price'],2);
        }
    	$this->assign('list', $list);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }

    //新增学员合同
    public function add(){
        $studentList=D('Students')->where(array("status"=>0))->select();
        $this->assign('studentList', $studentList);
        $this->display();
    }

    //新增学员合同提交
    public function add_post(){
        if (IS_POST) {
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
            $result=$this->studentContract_model->add($contracts);
            if ($result) {
                $finance['type']=1;
                $finance['source']=1;
                $finance['price']=$contracts['total_price'];
                $finance['project']=$contracts['name'];
                $finance['user_id']=$contracts['stu_id'];
                $finance['contract_id']=$result;
                D('Finance')->add($finance);
                $this->success("添加成功！",U("contract/index"));
            } else {
                $this->error("添加失败！");
            }

        }
    }

    //学员合同编辑
    public function edit(){
        $id=  I("get.id",0,'intval');
        $contract_model=M("student_contract");
        $info=$contract_model->where("id=$id")->find();

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
                $contracts['stu_id']=I('stu_id',$info['stu_id']);
                $contracts['name']=I('name',$info['name']);
                $contracts['total_price']=I('total_price',$info['total_price']);
                $contracts['class_number']=I('class_number',$info['class_number']);
                $contracts['start_time']=strtotime(I('start_time'));
                $contracts['end_time']=strtotime(I('end_time'));
                $contracts['update_time']=time();
                $uid=sp_get_current_admin_id();
                $contracts['update_admin']=$uid;
                $result=$this->studentContract_model ->save($contracts);
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
        $where=array();
        $request=I('request.');

        if(!empty($request['surplus_hour'])){
            $where['surplus_hour']=$request['surplus_hour'];
        }
        if(!empty($request['divide_class'])){
            $where['divide_class']=intval($request['divide_class']);
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

        $student_model=M("Contracts");

        $count=$student_model->where($where)->count();
        $page = $this->page($count, 20);

        $list = $student_model
            ->where($where)
            ->order("add_time DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        foreach ($list as $k=>$v){
            $list[$k]['apply_date']=date("Y-m-d",$v['apply_date']);
            if($v['sex']==0){
                $list[$k]['sex']="保密";
            }elseif($v['sex']==1){
                $list[$k]['sex']="男";
            }elseif($v['sex']==2){
                $list[$k]['sex']="女";
            }

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
        ContractModel::exportOrderList($list);
        exit;
    }

    //导入数据页面
//    public function import()
//    {
//        header("Content-Type:text/html;charset=utf-8");
//        $upload = new \Think\Upload();// 实例化上传类
//        $upload->maxSize   =     3145728 ;// 设置附件上传大小
//        $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类
//        $upload->savePath  =      '/'; // 设置附件上传目录
//        // 上传文件
//        $info   =   $upload->uploadOne($_FILES['excelData']);
//        $filename = './Uploads'.$info['savepath'].$info['savename'];
//        $exts = $info['ext'];
//        if(!$info) {// 上传错误提示错误信息
//            $this->error($upload->getError());
//        }else{// 上传成功
//            $import=ContractModel::import($filename, $exts);
//            $this->save_import($import);
//        }
//    }
    //导入数据页面
    public function import()
    {
        $file_info = $this->upload("excelData");
                print_r($file_info);die;

        if ($file_info["status"] == "1") {
            $updata["excelData"] = $file_info["data"];
        }

    }

    //保存导入数据
    public function save_import($data)
    {
        print_r($data);exit;

        $Contracts = M('Contracts');
        foreach ($data as $k=>$v){
            if($k >= 2){
                $title=$v['A'];
                $info[$k-2]['title'] = $title;

                $old_pno=$v['E'];
                $info[$k-2]['old_PNO'] = $old_pno;

                $brand_title=$v['C'];
                $brand_id = M('Brand')->where(array('title' => $brand_title))->getField('id');
                if($brand_id){
                    $info[$k-2]['brand_id'] = $brand_id;
                }else{
                    $new_brand_id = M('Brand')->add(array('title' => $brand_title, 'sort' => $k, 'add_time' => $add_time));
                    $info[$k-2]['brand_id'] = $new_brand_id;
                }

                $price=$v['D'];
                $info[$k-2]['price'] = $price;

                $type_titles=$v['F'];
                $type_array = explode(',', $type_titles);

                foreach ($type_array as $type_info){
                    $type_title = $type_info;
                    $type_id = M('Type')->where(array('title' => $type_title))->getField('id');
                    if($type_id){
                        $info[$k-2]['type_ids'] .= $type_id.',';
                    }else{
                        $new_type_id = M('Type')->add(array('title' => $type_title, 'sort' => $k, 'add_time' => $add_time));
                        $info[$k-2]['type_ids'] .= ','.$new_type_id.',';
                    }

                }

                $category_title=$v['G'];
                $category_id = M('Category')->where(array('title' => $category_title))->getField('id');
                if($category_id){
                    $info[$k-2]['category_id'] = $category_id;
                }else{
                    $new_category_id = M('Category')->add(array('title' => $category_title, 'sort' => $k, 'add_time' => $add_time));
                    $info[$k-2]['category_id'] = $new_category_id;
                }

                $pno=$v['B'];
                $result = $Goods->where(array('PNO' => $pno))->find();

                //print_r($info[$k-2]);exit;
                if($result){
                    //更新操作
                    $result = $Goods->where(array('PNO' => $pno))->save($info[$k-2]);
                }else{
                    //入库操作
                    $info[$k-2]['PNO'] = $pno;
                    $info[$k-2]['add_time'] = $add_time;

                    $result = $Goods->add($info[$k-2]);
                }


                //print_r($info);exit;



            }

        }

        if(false !== $result || 0 !== $result){
            $this->success('产品导入成功', 'Admin/Goods/index');
        }else{
            $this->error('产品导入失败');
        }
        //print_r($info);

    }

    public function regEasemob($phone, $password)
    {
        //定义一个要发送的目标URL；
        $url = "http://192.168.1.188:6017/api/v1/user/easemobReg";
        //定义传递的参数数组；
        $data['phone']=$phone;
        $data['pwd']=$password;
        //定义返回值接收变量；
        $this->http($url, $data);

    }

    /**
     * Created by PhpStorm.
     * User: dewey
     * Date: 2017/6/12
     * Time: 下午10:31
     * 发送HTTP请求方法
     * @param  string $url    请求URL
     * @param  array  $params 请求参数
     * @param  string $method 请求方法GET/POST
     * @return array  $data   响应数据
     */
    protected function http($url, $params, $method = 'GET', $header = array(), $multi = false){
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => $header
        );

        /* 根据请求类型设置特定参数 */
        switch(strtoupper($method)){
            case 'GET':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                //判断是否传输文件
                $params = $multi ? $params : http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                throw new Exception('不支持的请求方式！');
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error) throw new Exception('请求发生错误：' . $error);
        return  $data;
    }


    private function uploadOne($fileType) {
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = 1024 * 1024 * 20; // 设置附件上传大小 <= 20MB
        $upload->rootPath = './Upload/'; // 设置附件上传根目录
        $upload->autoSub = false;
        $upload->replace = true;
        $upload->savePath = './' . $fileType . '/' . date('Ymd', time()) . '/';
        $upload->saveRule = time();
        //上传单个文件
        $info = $upload->uploadOne($_FILES[$fileType]);
        if (!$info) {// 上传错误提示错误信息
            $hasError['status'] = "0";
        } else {// 上传成功 获取上传文件信息
            $hasError['status'] = "1";
            $hasError['data'] = 'Upload' . substr($upload->savePath, 1) . $info['savename'];
        }
        return $hasError;
    }

    private function upload()
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg', 'xls', 'xlsx');// 设置附件上传类型
        $upload->rootPath = './Uploads/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();
        if (!$info) {
            // 上传错误提示错误信息
            $this->error($upload->getError());
        } else {
            // 上传成功
            $this->success('上传成功!');
        }
    }



    //导出学员合同课时记录
    public function exportConsume(){
        $where=array();
        $request=I('request.');

        if(!empty($request['surplus_hour'])){
            $where['surplus_hour']=$request['surplus_hour'];
        }
        if(!empty($request['divide_class'])){
            $where['divide_class']=intval($request['divide_class']);
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

        $student_model=M("Contracts");

        $count=$student_model->where($where)->count();
        $page = $this->page($count, 20);

        $list = $student_model
            ->where($where)
            ->order("add_time DESC")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        foreach ($list as $k=>$v){
            $list[$k]['apply_date']=date("Y-m-d",$v['apply_date']);
            if($v['sex']==0){
                $list[$k]['sex']="保密";
            }elseif($v['sex']==1){
                $list[$k]['sex']="男";
            }elseif($v['sex']==2){
                $list[$k]['sex']="女";
            }

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
        ContractModel::exportOrderList($list);
        exit;
    }
}
