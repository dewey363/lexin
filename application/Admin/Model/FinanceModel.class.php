<?php
namespace Admin\Model;

use Common\Model\CommonModel;

class FinanceModel extends CommonModel {
    public function exportList($list) {
        $file_name = '财务列表' . date("Y-m-d")."---总数".count($list);
        header('Content-Encoding: UTF-8');
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=" . $file_name . ".xls");
        echo "\xEF\xBB\xBF";
        $excel_head = array(
            '业务类型',
            '收/支',
            '金额',
            '支付方式',
            '项目',
            '创建者',
            '更新者',
            '操作时间',
            '备注'
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
                $output .= "<td>" . $v['type'] . "</td>";
                $output .= "<td>" . $v['source'] . "</td>";
                $output .= "<td>" . $v['price'] . "</td>";
                $output .= "<td>" . $v['payType'] . "</td>";
                if(!empty($v['stu_name'])){
                    $output .= "<td>" .$v['stu_name']. "</td>";
                }else{
                    $output .= "<td></td>";
                }
                $output .= "<td>" . $v['admin_name'] . "</td>";
                $output .= "<td>" . $v['update_admin_name'] . "</td>";
                $output .= "<td>" . $v['update_time'] . "</td>";
                $output .= "<td>" . $v['project'] . "</td>";
                $output .= '</tr>';
            }
            $output .= '</table>';
        }
        echo $output;
    }

    public function getType()
    {
        $array=array(
                array(
                   "id"=>0,
                   "name"=>'其他'
                ),
                array(
                    "id"=>1,
                    "name"=>'学员收费'
                ),
                array(
                    "id"=>2,
                    "name"=>'学员退费'
                ),
                array(
                    "id"=>3,
                    "name"=>'市场'
                ),
                array(
                    "id"=>4,
                    "name"=>'人力'
                )
        );
        return $array;
    }

    public function getPayType()
    {
        $array=array(
            array(
                "id"=>0,
                "name"=>'请选择'
            ),
            array(
                "id"=>1,
                "name"=>'支付宝'
            ),
            array(
                "id"=>2,
                "name"=>'微信'
            ),
            array(
                "id"=>3,
                "name"=>'刷卡'
            ),
            array(
                "id"=>4,
                "name"=>'银行转帐'
            ),
            array(
                "id"=>5,
                "name"=>'现金'
            )
        );
        return $array;
    }

}