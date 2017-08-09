<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Admin\Model\StudentModel;

class StudentController extends AdminbaseController{


    protected $school_model;
    protected $staff_model;
    protected $class_model;
    protected $studentContract_model;
    public function _initialize() {
        parent::_initialize();
        $this->school_model = D("Admin/School");
        $this->staff_model = D("Admin/Staff");
        $this->class_model = D("Admin/Class");
        $this->studentContract_model = D("Admin/StudentContract");
    }
    
    // 学生管理列表
    public function index(){
        $where=array();
        $request=I('request.');

        if(!empty($request['keyword'])){
            $keyword=$request['keyword'];
            $keyword_complex=array();
            $keyword_complex['name']  = array('like', "%$keyword%");
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
    	$student_model=M("Students");
    	
    	$count=$student_model->where($where)->count();
    	$page = $this->page($count, 20);
    	
    	$list = $student_model
    	->where($where)
    	->order("add_time DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	foreach ($list as $k=>$v){
            $staffSql['status']=0;
            $staffSql['position']=3;
            $staffSql['id']=$v['course_consultant'];
            $course_consultant = D('staff')->where($staffSql)->find();
            $list[$k]['course_consultant']=$course_consultant['name'];
    	    $list[$k]['apply_date']=date("Y-m-d",$v['apply_date']);
            $list[$k]['class_name']="";
    	    if(!empty($v['class'])){
                $classSql['id']=$v['class'];
                $classInfo=$this->class_model
                    ->where($classSql)
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
        $adminId=sp_get_current_admin_id();
        $schoolSql=[];
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['school_id']=array(
                    array('in',$schoolId)
                );

                $schoolSql['id']=array(
                    array('in',$schoolId)
                );
            }
        }
        $where['position']=3;
        $schoolList=$this->school_model->where($schoolSql)->select();
        $staffList=$this->staff_model->where($where)->select();
        $this->assign("schoolList",$schoolList);
        $this->assign("staffList",$staffList);
        $this->display();
    }

    //新增学员提交
    public function add_post(){
        if (IS_POST) {
            $post=I('post.');
            $validate=StudentModel::validate($post);
            if(!empty($validate)){
                $this->error($validate);
            }
            $student['name']=I('name',"");
            $student['sex']=I('sex',1);
            $student['age']=I('age',0);
//            $student['class']=I('class',0);
            $student['number']=I('number',"");
            $student['mobile']=I('mobile',"");
//            $student['tuition']=I('tuition',"0.00");
//            $student['class_hour']=I('class_hour');
//            $student['unit_price']=I('unit_price');
            $student['apply_date']=strtotime(I('apply_date'));
            $student['course_consultant']=I('course_consultant');
            $student['fingerprint']=I('fingerprint');
            $student['kindergarten']=I('kindergarten');
//            $student['hire_purchase']=I('hire_purchase');
//            $student['time_consuming_reminder']=I('time_consuming_reminder');
//            $student['lecture_setting']=I('lecture_setting');
            $student['birthday']=strtotime(I('birthday'));
            $student['address']=I('address');
//            $student['card_info']=I('card_info');
//            $student['course']=I('course');
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
                    $appUser_model=M("app_user");
                    foreach ($parentName as $k=>$v){
                        $appUserInfo=$appUser_model->where(array("phone"=>$phone[$k]))->find();
                        if(empty($appUserInfo)){
                            $dataList[] = array(
                                'name'=>$v,
                                'phone'=>$phone[$k],
                                'relationship'=>$relationship[$k],
                                'guardian'=>$guardian[$k],
                                'stu_id'=>$result,
                                'user_status'=>2,
                                'password'=>sha1("111111"),
                                'create_time'=>time()
                            );
                            $user['phone']=$phone[$k];
                            $user['password']=sha1("111111");
                            $this->regEasemob($user['phone'],$user['password']);
                        }else{
                            $saveData= array(
                                'name'=>$v,
                                'relationship'=>$relationship[$k],
                                'guardian'=>$guardian[$k],
                                'stu_id'=>$result,
                            );
                            $appUser_model->where(array('phone'=>$phone[$k]))->save($saveData);
                        }
                    }
                    if(!empty($dataList)){
                        $appUser_model->addAll($dataList);
                    }

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
        $parentList=D('AppUser')->where(array("stu_id"=>$info['id']))->select();

        $info['apply_date']=date("Y-m-d",$info['apply_date']);
        $info['birthday']=date("Y-m-d",$info['birthday']);
        /***获取管理员id,判断对应所属学校start***/
        $adminId=sp_get_current_admin_id();
        $schoolSql=[];
        if($adminId !=1){
            $schoolId=get_current_school();
            if(!empty($schoolId)){
                $where['school_id']=array(
                    array('in',$schoolId)
                );

                $schoolSql['id']=array(
                    array('in',$schoolId)
                );
            }
        }
        $where['position']=3;
        /***获取管理员id,判断对应所属学校end***/
        $schoolList=$this->school_model->where($schoolSql)->select();
        foreach ($schoolList as $k=>$v){
            if($v['id']==$info['school']){
                $schoolList[$k]['selected']="selected";
            }else{
                $schoolList[$k]['selected']="";
            }
        }
        $staffList=$this->staff_model->where($where)->select();
        foreach ($staffList as $k=>$v){
            if($v['id']==$info['course_consultant']){
                $staffList[$k]['selected']="selected";
            }else{
                $staffList[$k]['selected']="";
            }
        }
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
            $post=I('post.');
            $validate=StudentModel::validate($post);
            if(!empty($validate)){
                $this->error($validate);
            }
            $student_model=M("Students");
            $student['id']=intval($_POST['id']);
            $info=$student_model->where(array("id"=>$student['id']))->find();
            if(!empty($info)){
                $student['name']=I('name',$info['name']);
                $student['sex']=I('sex',$info['sex']);
                $student['age']=I('age',$info['age']);
//                $student['class']=I('class');
                $student['number']=I('number',$info['number']);
                $student['mobile']=I('mobile',$info['mobile']);
//                $student['tuition']=I('tuition',$info['tuition']);
//                $student['class_hour']=I('class_hour',$info['class_hour']);
//                $student['unit_price']=I('unit_price',$info['unit_price']);
//                $student['class_end']=I('class_end',$info['class_end']);
                $student['apply_date']=strtotime(I('apply_date'));
                $student['course_consultant']=I('course_consultant');
                $student['fingerprint']=I('fingerprint');
                $student['kindergarten']=I('kindergarten');
//                $student['hire_purchase']=I('hire_purchase');
//                $student['time_consuming_reminder']=I('time_consuming_reminder');
//                $student['lecture_setting']=I('lecture_setting');
                $student['birthday']=strtotime(I('birthday'));
                $student['address']=I('address');
//                $student['card_info']=I('card_info');
                $student['school']=I('school');
//                $student['course']=I('course');
                //获取家长信息
                $parentName=I('parentName');
                $phone=I('phone');
                $relationship=I('relationship');
                $guardian=I('guardian');
                $result=$student_model->save($student);
                if ($result!==false) {
                    //添加／编辑家长信息
                    if(!empty($parentName)){
                        $dataList=[];
                        $appUser_model=M("app_user");
                        foreach ($parentName as $k=>$v){
                            $appUserInfo=$appUser_model->where(array("phone"=>$phone[$k]))->find();
                            if(empty($appUserInfo)){
                                $dataList[] = array(
                                    'name'=>$v,
                                    'phone'=>$phone[$k],
                                    'relationship'=>$relationship[$k],
                                    'guardian'=>$guardian[$k],
                                    'stu_id'=>$student['id'],
                                    'user_status'=>2,
                                    'password'=>sha1("111111"),
                                    'create_time'=>time()
                                );
                                $user['phone']=$phone[$k];
                                $user['password']=sha1("111111");
                                $this->regEasemob($user['phone'],$user['password']);
                            }else{
                                $saveData= array(
                                    'name'=>$v,
                                    'relationship'=>$relationship[$k],
                                    'guardian'=>$guardian[$k],
                                    'stu_id'=>$student['id'],
                                );
                                $appUser_model->where(array('phone'=>$phone[$k]))->save($saveData);
                            }

                        }
                        if(!empty($dataList)){
                            $appUser_model->addAll($dataList);
                        }

                    }

                    if($student['school'] !=$info['school']){
                        $cWhere['stu_id']=$student['id'];
                        $contracts['school']=$student['school'];
                        $this->studentContract_model ->where($cWhere)->save($contracts);

                        $scList=$this->studentContract_model ->where(array("stu_id"=>$student['id']))->field('id')->select();
                        $conId=[];
                        if(!empty($scList)){
                            foreach ($scList as $v){
                                $conId[]=$v['id'];
                            }
                        }
                        if(!empty($conId)){
                            //原有分班数据软删除
                            $classStudent=M('ClassStudent');
                            $csWhere['contract_id']=array('in',implode(',',$conId));
                            $csWhere['is_del']=0;
                            $csArr['is_del']=1;
                            $classStudent->where($csWhere)->save($csArr);
                        }
                        $this->success("转校成功！");
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
        $contract_model=M("Students");
        $result=$contract_model->save($contracts);
        if ($result) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
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
            foreach ($list as $k=>$v){
                $classSql['id']=$v['class_id'];
                $class=$this->class_model->where($classSql)->field('name,course,teacher')->find();
                $stuSql['id']=$v['stu_id'];
                $student=D('Students')->where($stuSql)->field('name')->find();
                $list[$k]['add_time']=date("Y-m-d H:i:s",$v['add_time']);
                $list[$k]['student_name']=$student['name'];
                $list[$k]['class_name']=$class['name'];
                $list[$k]['course']=$class['course'];
                $staffSql['status']=0;
                $staffSql['position']=1;
                $staffSql['id']=$class['teacher'];
                $teacher= $this->staff_model->where($staffSql)->find();
                $list[$k]['teacher_name']=$teacher['name'];
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

    //导出学员课时记录
    public function exportConsume(){
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
            foreach ($list as $k=>$v){
                $classSql['id']=$v['class_id'];
                $class=$this->class_model->where($classSql)->field('name,course,teacher')->find();
                $student=D('Students')->where(array("id"=>$v['stu_id']))->field('name')->find();
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
                $course_consultant = $this->staff_model->where($staffSql)->find();
                $list[$k]['teacher']=$course_consultant['name'];
                if($v['type']==0){
                    $list[$k]['type']="正常上课";
                }elseif($v['type']==1){
                    $list[$k]['type']="补课";
                }elseif($v['type']==2){
                    $list[$k]['type']="缺课";
                }
            }
        }
        StudentModel::exportConsum($list,$count);
        exit;
    }

    public function regEasemob($phone, $password)
    {
        //定义一个要发送的目标URL；
        $url = "http://111.231.63.219:6017/api/v1/user/easemobReg";
        //定义传递的参数数组；
        $data['phone']=$phone;
        $data['pwd']=$password;
        //定义返回值接收变量；
        StudentModel::http($url, $data);

    }
    //批量导出学员信息
    public function export(){
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

            $parentInfo=D('app_user')
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
        StudentModel::exportList($list);
        exit;
    }
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




}
