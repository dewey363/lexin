<?php
namespace Index\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        header("location: http://crms.dev/admin");
    }
}