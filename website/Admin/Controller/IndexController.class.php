<?php
/**
 * Created by PhpStorm.
 * User: arpeng
 * Date: 15/11/10
 * Time: 下午6:37
 */
namespace Admin\Controller;

use Common\Controller\Admin\CommonController;

class IndexController extends CommonController
{
    public function _initialize()
    {

    }


    /**
     * Introduction: 后台首页
     * User: arpeng
     * Date: 15/11/12
     * Time: 下午9:59
     * Last date: 15/11/12 下午9:59
     * Last user: arpeng
     */
    public function index()
    {
        $this->TPL_Head();
        include T('index');
        $this->TPL_Foot();
    }
}