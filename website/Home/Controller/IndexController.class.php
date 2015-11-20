<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        echo '<pre>';
        var_dump($_SERVER);
    }
}