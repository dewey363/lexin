<?php
namespace Admin\Model;

use Common\Model\CommonModel;

class StaffModel extends CommonModel {

    protected function _before_write(&$data) {
        parent::_before_write($data);
    }
}