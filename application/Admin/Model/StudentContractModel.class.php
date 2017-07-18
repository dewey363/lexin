<?php
namespace Admin\Model;

use Common\Model\CommonModel;

class StudentContractModel extends CommonModel {

    public function validate($array) {
        if($array['stu_id']==0){
            return "请选择学员！";
        }

        if(empty($array['name'])){
            return "合同名称不能为空！";
        }

        if(empty($array['total_price'])){
            return "合同金额不能为空！";
        }

        if(empty($array['class_number'])){
            return "课时数不能为空！";
        }

        if(empty($array['start_time'])){
            return "合同开始时间不能为空！";
        }

        if(empty($array['end_time'])){
            return "合同结束时间不能为空！";
        }

        if($array['start_time']>$array['end_time']){
            return "合同开始时间不能大于结束时间！";
        }

        if($array['end_time'] < date('Y-m-d')){
            return "合同结束时间不能小于当前时间！";
        }
    }

    public function exportList($list) {
        $file_name = '合同列表' . date("Y-m-d")."---总数".count($list);
        header('Content-Encoding: UTF-8');
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=" . $file_name . ".xls");
        echo "\xEF\xBB\xBF";
        $excel_head = array(
            '学生姓名',
            '合同名称',
            '合同总金额',
            '合同单价',
            '课时',
            '开始时间',
            '结束时间',
            '操作者'
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
                $output .= "<td>" . $v['stu_name'] . "</td>";
                $output .= "<td>" . $v['name'] . "</td>";
                $output .= "<td>" . $v['total_price'] . "</td>";
                $output .= "<td>" . $v['price'] . "</td>";
                $output .= "<td>" . $v['class_number'] . "</td>";
                $output .= "<td>" . $v['start_time'] . "</td>";
                $output .= "<td>" . $v['end_time'] . "</td>";
                $output .= "<td>" . $v['admin_name'] . "</td>";
                $output .= '</tr>';
            }
            $output .= '</table>';
        }
        echo $output;
    }

	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
}