<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Admin\Model\StudentModel;

class StudentController extends AdminbaseController{


    protected $school_model;
    protected $staff_model;
    protected $class_model;
    public function _initialize() {
        parent::_initialize();
        $this->school_model = D("Admin/School");
        $this->staff_model = D("Admin/Staff");
        $this->class_model = D("Admin/Class");
    }
    
    // 学生管理列表
    public function index(){
        $where=array();
        $request=I('request.');
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
                $classInfo=$this->class_model
                    ->where(array("id"=>$v['class']))
                    ->field("name")
                    ->find();
                $list[$k]['class_name']=$classInfo['name'] ;
            }
        }
    	$this->assign('list', $list);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }

    //新增学员
    public function add(){
        $schoolList=$this->school_model->select();
        $staffList=$this->staff_model->where(array("position"=>3))->select();
        $classList=$this->class_model->select();
        $this->assign("classList",$classList);
        $this->assign("schoolList",$schoolList);
        $this->assign("staffList",$staffList);
        $this->display();
    }

    //新增学员提交
    public function add_post(){
        if (IS_POST) {
            $student['name']=I('name',"");
            $student['sex']=I('sex',1);
            $student['age']=I('age',0);
            $student['class']=I('class',0);
            $student['number']=I('number',"");
            $student['mobile']=I('mobile',"");
            $student['tuition']=I('tuition',"0.00");
            $student['class_hour']=I('class_hour');
            $student['unit_price']=I('unit_price');
            $student['apply_date']=strtotime(I('apply_date'));
            $student['course_consultant']=I('course_consultant');
            $student['fingerprint']=I('fingerprint');
            $student['kindergarten']=I('kindergarten');
            $student['hire_purchase']=I('hire_purchase');
            $student['time_consuming_reminder']=I('time_consuming_reminder');
            $student['lecture_setting']=I('lecture_setting');
            $student['birthday']=strtotime(I('birthday'));
            $student['address']=I('address');
            $student['card_info']=I('card_info');
            $student['course']=I('course');
            $student['school']=I('school');
            //获取家长信息
            $parentName=I('parentName');
            $phone=I('phone');
            $relationship=I('relationship');
            $guardian=I('guardian');

            $student_model=M("Students");
            $result=$student_model->add($student);
            if ($result) {
                if(!empty($parentName)){
                    $dataList=[];
                    foreach ($parentName as $k=>$v){
                        $dataList[] = array(
                            'name'=>$v,
                            'phone'=>$phone[$k],
                            'relationship'=>$relationship[$k],
                            'guardian'=>$guardian[$k],
                            'stu_id'=>$result
                        );
                    }
                    if(!empty($dataList)){
                        D('Parents')->addAll($dataList);
                    }

                }

                if(!empty($phone)){
                    $appUser_model=M("app_user");
                    foreach ($phone as $v){
                        $appUserInfo=$appUser_model->where(array("phone"=>$v))->find();
                        if(empty($appUserInfo)){
                            $user['phone']=$v;
                            $user['user_status']=2;
                            $user['user_status']=2;
                            $user['password']=sha1("111111");
                            $user['create_time']=time();
                            $appUser_model->add($user);
                            $this->regEasemob($user['phone'],$user['password']);
                        }
                    }

                }

                if($student['class']>0){
                    $data['stu_id']=$result;
                    $data['class_id']=$student['class'];
                    D('class_student')->add($data);
                }

                $this->success("添加成功！",U("student/index"));
            } else {
                $this->error("添加失败！");
            }

        }
    }

    //学员编辑
    public function edit(){
        $id=  I("get.id",0,'intval');
        $student_model=M("Students");
        $info=$student_model->where("id=$id")->find();
        $parentList=D('Parents')->where(array("stu_id"=>$info['id']))->select();

        $info['apply_date']=date("Y-m-d",$info['apply_date']);
        $info['birthday']=date("Y-m-d",$info['birthday']);
        $schoolList=$this->school_model->select();
        foreach ($schoolList as $k=>$v){
            if($v['id']==$info['school']){
                $schoolList[$k]['selected']="selected";
            }else{
                $schoolList[$k]['selected']="";
            }
        }
        $staffList=$this->staff_model->where(array("position"=>3))->select();
        foreach ($staffList as $k=>$v){
            if($v['id']==$info['course_consultant']){
                $staffList[$k]['selected']="selected";
            }else{
                $staffList[$k]['selected']="";
            }
        }
        $classList=$this->class_model->select();
        foreach ($classList as $k=>$v){
            if($v['id']==$info['class']){
                $classList[$k]['selected']="selected";
            }else{
                $classList[$k]['selected']="";
            }
        }
        $this->assign("classList",$classList);
        $this->assign("schoolList",$schoolList);
        $this->assign("staffList",$staffList);
        $this->assign("info",$info);
        $this->assign("parentList",$parentList);
        $this->assign("id",$id);
        $this->display();
    }

    //学员编辑提交
    public function edit_post(){
        if (IS_POST) {
            $student_model=M("Students");
            $student['id']=intval($_POST['id']);
            $info=$student_model->where(array("id"=>$student['id']))->find();
            if(!empty($info)){
                $student['name']=I('name',$info['name']);
                $student['sex']=I('sex',$info['sex']);
                $student['age']=I('age',$info['age']);
                $student['class']=I('class');
                $student['number']=I('number',$info['number']);
                $student['mobile']=I('mobile',$info['mobile']);
                $student['tuition']=I('tuition',$info['tuition']);
                $student['class_hour']=I('class_hour',$info['class_hour']);
                $student['unit_price']=I('unit_price',$info['unit_price']);
                $student['class_end']=I('class_end',$info['class_end']);
                $student['apply_date']=strtotime(I('apply_date'));
                $student['course_consultant']=I('course_consultant');
                $student['fingerprint']=I('fingerprint');
                $student['kindergarten']=I('kindergarten');
                $student['hire_purchase']=I('hire_purchase');
                $student['time_consuming_reminder']=I('time_consuming_reminder');
                $student['lecture_setting']=I('lecture_setting');
                $student['birthday']=strtotime(I('birthday'));
                $student['address']=I('address');
                $student['card_info']=I('card_info');
                $student['school']=I('school');
                $student['course']=I('course');
                //获取家长信息
                $parentName=I('parentName');
                $phone=I('phone');
                $relationship=I('relationship');
                $guardian=I('guardian');
                $result=$student_model->save($student);
                if ($result!==false) {
                    //添加／编辑家长信息
                    if(!empty($parentName)){
                        $parentModel=D('Parents');
                        foreach ($parentName as $k=>$v){
                            $parentInfo=$parentModel->where(array("phone"=>$phone[$k]))->find();
                            $dataList = array(
                                'name'=>$v,
                                'phone'=>$phone[$k],
                                'relationship'=>$relationship[$k],
                                'guardian'=>$guardian[$k],
                                'stu_id'=>$student['id']
                            );
                            if(empty($parentInfo)){
                                $parentModel->add($dataList);
                            }else{
                                $parentModel->where(array("phone"=>$phone[$k]))->save($dataList);
                            }
                        }

                    }
                    //创建app用户账号
                    if(!empty($phone)){
                        $appUser_model=M("app_user");
                        foreach ($phone as $v){
                            $appUserInfo=$appUser_model->where(array("phone"=>$v))->find();
                            if(empty($appUserInfo)){
                                $user['phone']=$v;
                                $user['user_status']=2;
                                $user['user_status']=2;
                                $user['password']=sha1("111111");
                                $user['create_time']=time();
                                $appUser_model->add($user);
                                $this->regEasemob($user['phone'],$user['password']);
                            }
                        }

                    }

                    if($student['class']>0){
                        $data['stu_id']=$student['id'];
                        $data['class_id']=$student['class'];
                        $classStu=D('class_student')->where($data)->find();
                        if(empty($classStu)){
                            D('class_student')->add($data);
                        }else{
                            if($classStu['status']==2){
                                D('class_student')->where($data)->save(array("status"=>1));
                            }
                        }

                        if($info['class']>0 && $student['class'] !=$info['class']){
                            $where['stu_id']=$student['id'];
                            $where['class_id']=$info['class'];
                            D('class_student')->where($where)->save(array("status"=>2));
                        }

                    }

                    if($student['class']==0){
                        $where['stu_id']=$student['id'];
                        $where['class_id']=$info['class'];
                        D('class_student')->where($where)->save(array("status"=>2));
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
        $student_model=M("Students");
        if ($student_model->delete($id)!==false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

    // 停课／开课
    public function suspend(){
        $student_model=M("Students");
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

    //消耗课时记录列表
    public function classConsume(){
        $stuId=  I("get.stuId",0,'intval');
        $request=I('request.');
        $studentId=0;
        if($stuId>0){
            $studentId=$stuId;
        }elseif($request['stuId']>0){
            $studentId=$request['stuId'];
        }
        if($studentId>0){
            $where['stu_id']=$studentId;
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
            $this->assign('stuId', $studentId);
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
    //批量导出学员信息
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

        $student_model=M("Students");

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
        StudentModel::exportOrderList($list);
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
//            $import=StudentModel::import($filename, $exts);
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

        $Students = M('Students');
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



    //导出学员课时记录
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

        $student_model=M("Students");

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
        StudentModel::exportOrderList($list);
        exit;
    }
}
