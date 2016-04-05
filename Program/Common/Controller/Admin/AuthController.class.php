<?php
/**
 * User: arpeng
 * Date: 15/11/13
 * Time: 上午11:47
 * Last date: 15/11/13 上午11:47
 * Last user: arpeng
 */

namespace Common\Controller\Admin;

use Common\Model\Admin\AdministratorModel;
use Think\Controller;
use Think\Think;

class AuthController extends Controller
{
    public $auid,$uid,$username, $userinfo,$AUTH;

    public function __construct()
    {

        parent::__construct();

        $this->AUTH = new \Think\Auth(C('Administrator'));//\Think\Auth(C('Administrator'));

        $this->checkLogin();


        $this->checkAuth();

        $this->userinfo();

    }
    /**
     * Introduction: 登录验证
     * User: arpeng
     * Date: 15/11/14
     * Time: 08:27
     * Last date: 15/11/14 08:27
     * Last user: arpeng
     */
    public function checkLogin(){

        //验证登录
        $this->auid = session('auid');

        $this->uid = session('uid');

        $this->username = session('username');

        if(!$this->auid || !$this->username || !$this->uid){

            if(IS_AJAX){

                die('timeout');

            }

            $this->error('登录已过期!',U('Admin/Index/Sign/login'));

        }

    }
    /**
     * Introduction: 权限验证
     * User: arpeng
     * Date: 15/11/14
     * Time: 08:27
     * Last date: 15/11/14 08:27
     * Last user: arpeng
     */
    public function checkAuth(){

        //验证权限
        $no_check_1 = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME) == 'admin/index/index/loadmenu';
        $no_check_2 = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME) == 'admin/index/index/index';
        $no_check_3 = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME) == 'admin/index/index/_index';
        if($no_check_1 or $no_check_2 or $no_check_3)
            return true;

        if(!$this->AUTH->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,session('uid'))){

            //IS_AJAX && $this->ajaxReturn(['code'=>300,'msg'=>'权限不足']);


            $this->error('权限不足','Admin/Index/Index/index');

        }

    }
    /**
     * Introduction: 当前管理员信息
     * User: arpeng
     * Date: 15/11/14
     * Time: 08:27
     * Last date: 15/11/14 08:29
     * Last user: arpeng
     */
    public function userinfo(){

        $Administrator = new AdministratorModel();//D('Admin/Administrator');//new \Admin\Model\AdministratorModel();//('Administrator','',C('CODE_ADMIN'));

        $this->userinfo = $Administrator
            ->where(['username'=>session('username')])
            ->field('
                                    username,
                                    userid,
                                    truename,
                                    nicname,
                                    auid,
                                    mobile,
                                    email,
                                    userid,
                                    regip,
                                    nowip,
                                    lastip,
                                    regtime,
                                    nowtime,
                                    status,
                                    lasttime')
            ->find();

        //账户被禁用
        if(!$this->userinfo['status'] && $this->userinfo['userid'] != 1){

            $this->error('您的账户已被禁用','Admin/Index/Sign/login');
            
        }
    }
}