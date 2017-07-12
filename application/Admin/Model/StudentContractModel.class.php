<?php
namespace Admin\Model;

use Common\Model\CommonModel;

class StudentContractModel extends CommonModel {
    
    //自动验证
    protected $_validate = array(
        array('name', 'require', '合同名称不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('total_price', 'require', '合同金额不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('class_number', 'require', '课时数不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('start_time', 'require', '合同开始时间不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('end_time', 'require', '合同结束时间不能为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
}