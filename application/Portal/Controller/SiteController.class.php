<?php
// +----------------------------------------------------------------------
// | WinLangCMS [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.winlang.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\HomebaseController;

class SiteController extends HomebaseController {

	// 授权验证
	public function index() {
	    //http://127.0.0.3/index.php?m=site&a=index&li_license=6a445241d4e2c5d84b81012086e846c4&li_site=120.25.254.69
	    $lilicense=I('get.li_license',null,'string');
        $site=I('get.li_site',null,'string');
        $license_model= M("License");
        $term=$license_model
            ->where(array('li_license'=>$lilicense,"li_site"=>$site))
            ->find();
		if(empty($term) || $term['status']==0){
		    echo 0;
		}elseif($term['status']==1){
            echo 1;
        }

	}
}
