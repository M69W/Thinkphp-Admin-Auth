<?php
/**
 * User: arpeng
 * Date: 15/11/13
 * Time: 上午11:47
 * Last date: 15/11/13 上午11:47
 * Last user: arpeng
 */

namespace Common\Controller\Admin;

use Think\Controller;

class AuthController extends Controller
{
    static public $auid,$uid,$username, $userinfo,$AUTH;
    public function __construct()
    {
        parent::__construct();
        $this->AUTH = new \Think\Auth(C('SKY_ADMIN'));
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
        if(!$this->auid || !$this->username){
            $this->error('登录已过期!',U('Login/index'));
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
        if(!$this->AUTH->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,session('uid'))){
            IS_AJAX && $this->ajaxReturn('权限不足');
            $this->error('权限不足!');
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
        $Administrator = new \Admin\Logic\AdministratorLogic();//('Administrator','',C('SKY_ADMIN'));
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
        if(!$this->userinfo['status']){
            $this->error('您的账户已被禁用','Login/index');
        }
    }
}