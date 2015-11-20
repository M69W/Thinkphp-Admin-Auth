<?php
/**
 * User: arpeng
 * Date: 15/11/12
 * Time: 下午10:38
 * Last date: 15/11/12 下午10:38
 * Last user: arpeng
 */
namespace Admin\Controller;

use Common\Controller\Admin\CommonController;

class BasicController extends CommonController
{
    public function _initialize()
    {

    }
    /**
     * Introduction: 基础管理首页
     * User: arpeng
     * Date: 15/11/12
     * Time: 下午9:59
     * Last date: 15/11/12 下午9:59
     * Last user: arpeng
     */
    public function index()
    {
        //$this->redirect('Administrator/index');
        $this->TPL_Head();
        $this->TPL_Foot();
    }
}