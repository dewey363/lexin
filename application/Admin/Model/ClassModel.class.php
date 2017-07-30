<?php
namespace Admin\Model;

use Common\Model\CommonModel;

class ClassModel extends CommonModel {

    public function exportList($list) {
        $file_name = '班级列表' . date("Y-m-d");
        header('Content-Encoding: UTF-8');
        header("Content-type:application/vnd.ms-excel;charset=UTF-8");
        header("Content-Disposition:filename=".$file_name.".xls");
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
}