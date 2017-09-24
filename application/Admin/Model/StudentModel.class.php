<?php
namespace Admin\Model;

use Common\Model\CommonModel;

class StudentModel extends CommonModel {

    protected function _before_write(&$data) {
        parent::_before_write($data);
    }

    public function getStuInfo($id)
    {
        $info=array();
        if($id>0){
            $student_model=M("Students");
            $info=$student_model->where("id=$id")->find();
        }
        return $info;

    }

    public function exportList($list) {
        $file_name = '学员列表' . date("Y-m-d");
        header('Content-Encoding: UTF-8');
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=" . $file_name . ".xls");
        echo "\xEF\xBB\xBF";
        $excel_head = array(
                            '姓名',
                            '性别',
                            '年龄',
                            '学员编号',
                            '电话',
                            '学费',
                            '购买课时',
                            '单课时价格',
                            '已消耗课时',
                            '班级',
                            '报名日期',
                            '课程顾问',
                            '家长姓名',
                            '家长电话',
                            '关系'
            );
        $output = '<table border="1">';
        //标题头
        $output .= '<tr>';
        foreach ($excel_head as $v) {
            $output .= "<th>" . $v . "</th>";
        }
        $output .= '</tr>';
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                $output .= '<tr>';
                $output .= "<td>" . $v['name'] . "</td>";
                $output .= "<td>" . $v['sex'] . "</td>";
                $output .= "<td>" . $v['age'] . "</td>";
                $output .= "<td>" . $v['number'] . "</td>";
                $output .= "<td>" . $v['mobile'] . "</td>";
                $output .= "<td>" . $v['tuition'] . "</td>";
                $output .= "<td>" . $v['class_hour'] . "</td>";
                $output .= "<td>" . $v['unit_price'] . "</td>";
                $output .= "<td>" . $v['consume_hour'] . "</td>";
                $output .= "<td>" . $v['class_name'] . "</td>";
                $output .= "<td>" . $v['apply_date'] . "</td>";
                $output .= "<td>" . $v['course_consultant'] . "</td>";
                $output .= "<td>" . $v['parent_name']. "</td>";
                $output .= "<td>" . $v['parent_phone']. "</td>";
                $output .= "<td>" . $v['relationship']. "</td>";
                $output .= '</tr>';
            }
            $output .= '</table>';
        }
        echo $output;
    }
    public function exportConsum($list,$count) {
        $file_name = '课时消耗列表' . date("Y-m-d")."共".$count."条";
        header('Content-Encoding: UTF-8');
        header("Content-type:application/vnd.ms-excel;charset=UTF-8");
        header("Content-Disposition:filename=".$file_name.".xls");
        echo "\xEF\xBB\xBF";
        $excel_head = array(
            '姓名',
            '班级名',
            '课程名称',
            '老师',
            '消耗课时',
            '单价',
            '卡号',
            '消耗时间',
            '消耗类型'
        );
        $output = '<table border="1">';
        //标题头
        $output .= '<tr>';
        foreach ($excel_head as $v) {
            $output .= "<th>" . $v . "</th>";
        }
        $output .= '</tr>';
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                $output .= '<tr>';
                $output .= "<td>" . $v['student_name'] . "</td>";
                $output .= "<td>" . $v['class_name'] . "</td>";
                $output .= "<td>" . $v['course'] . "</td>";
                $output .= "<td>" . $v['teacher'] . "</td>";
                $output .= "<td>" . $v['class_hour'] . "</td>";
                $output .= "<td>" . $v['price'] . "</td>";
                $output .= "<td>" . $v['card_info'] . "</td>";
                $output .= "<td>" . $v['add_time'] . "</td>";
                $output .= "<td>" . $v['type'] . "</td>";
                $output .= '</tr>';
            }
            $output .= '</table>';
        }
        echo $output;
    }

    public function validate($array) {

        if(empty($array['name'])){
            return "学生姓名不能为空！";
        }

        if(empty($array['apply_date'])){
            return "报名日期不能为空！";
        }

        if($array['school']==0){
            return "请选择所属学校！";
        }

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
    public function http($url, $params, $method = 'GET', $header = array(), $multi = false){
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
}