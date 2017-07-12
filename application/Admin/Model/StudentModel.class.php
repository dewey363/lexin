<?php
namespace Admin\Model;

use Common\Model\CommonModel;

class StudentModel extends CommonModel {

    protected function _before_write(&$data) {
        parent::_before_write($data);
    }

    public function getStuInfo($id)
    {
        $info=[];
        if($id>0){
            $student_model=M("Students");
            $info=$student_model->where("id=$id")->find();
        }
        return $info;

    }

    public function exportOrderList($list) {
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
}