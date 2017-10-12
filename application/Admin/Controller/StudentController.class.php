<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Admin\Model\StudentModel;

class StudentController extends AdminbaseController{


    protected $school_model,$staff_model,$class_model,$studentContract_model;
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
        $where['is_del']=0;
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
            $conSql['is_del']=0;
            $conSql['stu_id']=$v['id'];
            $contract= $this->studentContract_model->where($conSql)->field('course,class')->select();
            $course=array();
            $classId=array();
            foreach ($contract as $vo){
                $course[]=$vo['course'];
                $classId[]=$vo['class'];
            }
            $list[$k]['course']=empty($course) ? '':implode(',',$course);
            $list[$k]['class']=empty($classId) ? '未分班':'已分班';
        }
    	$this->assign('list', $list);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }

    //新增学员
    public function add(){
        $adminId=sp_get_current_admin_id();
        $schoolSql=array();
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
            $student['number']=date('Ymd').rand(00000,99999);
            $stuInfos=$student_model->where(array("number" => $student['number'],'is_del'=>0))->find();
            if(!empty($stuInfos)){
                $randNumber=$this->get_rand_num($stuInfos['number']);
                $student['number'] = date('Ymd').$randNumber;
            }
            $result=$student_model->add($student);
            if ($result) {
                if(!empty($parentName)){
                    $dataList=array();
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
        $schoolSql=array();
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
            $info=$student_model->where(array("id"=>$student['id'],'is_del'=>0))->find();
            if(!empty($info)){
                $student['name']=I('name',$info['name']);
                $student['sex']=I('sex',$info['sex']);
                $student['age']=I('age',$info['age']);
//                $student['class']=I('class');
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
                if(empty($info['number'])){
                    $student['number']=date('Ymd').rand(00000,99999);
                    $stuInfos=$student_model->where(array("number" => $student['number'],'is_del'=>0))->find();
                    if(!empty($stuInfos)){
                        $randNumber=$this->get_rand_num($stuInfos['number']);
                        $student['number'] = date('Ymd').$randNumber;
                    }
                }

                $result=$student_model->save($student);
                if ($result!==false) {
                    //添加／编辑家长信息
                    if(!empty($parentName)){
                        $dataList=array();
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
                        $conId=array();
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
    public function upload()
    {
        header("Content-Type:text/html;charset=utf-8");
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->exts = array(
            'xls',
            'xlsx'
        ); // 设置附件上传类
        $upload->savePath = '/'; // 设置附件上传目录
        // 上传文件
        $info = $upload->uploadOne($_FILES['excelData']);
        $filename = './Uploads' . $info['savepath'] . $info['savename'];
        $exts = $info['ext'];
        // print_r($info);exit;
        if (! $info) { // 上传错误提示错误信息
            $this->error($upload->getError());
        } else { // 上传成功
            $this->import($filename, $exts);
            $this->success('导入成功');
        }
    }

    protected function import($filename, $exts = 'xls')
    {
        // 导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        vendor('PHPExcel.PHPExcel');
        // 创建PHPExcel对象，注意，不能少了\
        $PHPExcel = new \PHPExcel();
        // 如果excel文件后缀名为.xls，导入这个类
        if ($exts == 'xls') {
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } else
            if ($exts == 'xlsx') {
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }
        // 载入文件
        $PHPExcel = $PHPReader->load($filename);
        // 获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        // 获取总列数
        $allColumn = $currentSheet->getHighestColumn();
        // 获取总行数
        $allRow = $currentSheet->getHighestRow();
        // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow ++) {
            // 从哪列开始，A表示第一列
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn ++) {
                // 数据坐标
                $address = $currentColumn . $currentRow;
                // 读取到的数据，保存到数组$arr中
                $data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
            }
        }
        print_r($data);die;
        $this->save_import($data);
    }

    public function save_import($data)
    {
        foreach ($data as $key => $val) {
            if ($key > 1) {
                $school = $val['E'];
                $schoolInfo = $this->school_model->where(array("name" => $school))->find();
                if (!empty($schoolInfo)) {
                    $data[$key]['E'] = $schoolInfo['id'];
                    $stuInfo=D('Students')->where(array("mobile" => $val['D'],'school'=>$schoolInfo['id'],'is_del'=>0))->find();
                    if(!empty($stuInfo)){
                        unset($data[$key]);
                    }
                } else {
                    unset($data[$key]);
                }
            }
        }
        foreach ($data as $key => $val) {
            if ($key > 1) {
                $student['name'] = $val['A'];
                $student['sex'] = $val['B'];
                $student['age'] = $val['C'];
                $randNumber=$this->get_rand_num();
                $student['number'] = date('Ymd').$randNumber;
                $student['mobile'] = $val['D'];
                $student['school'] =$val['E'];
                $student['apply_date'] =strtotime($val['F']);
//                $student['course_consultant'] = $val['G'];

                $parentName = $val['G'];
                $parentPhone = $val['H'];
                $relationship = $val['I'];
                $student_model=M("Students");
                $stuInfos=$student_model->where(array("number" => $student['number']))->find();
                if(!empty($stuInfos)){
                    $randNumber=$this->get_rand_num($stuInfos['number']);
                    $student['number'] = date('Ymd').$randNumber;
                }
                $result=$student_model->add($student);
                if($result){
                    $appUser_model=M("app_user");
                    $appUserInfo=$appUser_model->where(array("phone"=>$parentPhone))->find();
                    if(empty($appUserInfo)){
                        $user = array(
                            'name'=>$parentName,
                            'phone'=>$parentPhone,
                            'relationship'=>$relationship,
                            'guardian'=>1,
                            'stu_id'=>$result,
                            'user_status'=>1,
                            'password'=>sha1("111111"),
                            'create_time'=>time()
                        );
                        $appUser_model->add($user);

                        $user['phone']=$parentPhone;
                        $user['password']=sha1("111111");
                        $this->regEasemob($user['phone'],$user['password']);
                    }else{
                        $saveData= array(
                            'name'=>$parentName,
                            'relationship'=>$relationship,
                            'guardian'=>1,
                            'stu_id'=>$result,
                        );
                        $appUser_model->where(array('phone'=>$parentPhone))->save($saveData);
                    }
                }
            }
        }
    }

    /**
     * 生成一个00000-99999随机数。
     */
    private function get_rand_num($number=0)
    {
        $num = rand(00000,99999);
        if($num == $number)
        {
            $num = $this->get_rand_num();
        }
        return $num;
    }
}
