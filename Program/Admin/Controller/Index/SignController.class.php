<?php
/**
 * Created by PhpStorm.
 * User: arpeng
 * Date: 16/4/3
 * Time: 下午10:48
 */

namespace Admin\Controller\Index;

use Think\Controller;

class SignController extends Controller
{
    /**
     * @introduction: 登录界面
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @return array
     */
    public function login()
    {
        if(IS_POST){

            $data = I('post.data');
            if(!$data['username'])
                $this->ajaxReturn(['code' => 300, 'msg' => '用户名不能为空']);

            if(!$data['password'])
                $this->ajaxReturn(['code' => 300, 'msg' => '密码不能为空']);

            if(!$data['authcode'])
                $this->ajaxReturn(['code' => 300, 'msg' => '验证码不能为空']);

            $logic = D('Administrator','Logic');

            if(!$info = $logic::getOneData(['username'=>$data['username']],'status,auid,userid,nowip,nowtime,status,password,encrypt,loginnums'))
                $this->ajaxReturn(['code' => 300, 'msg' => '管理员不存在']);


            if(!$this->checkAuthcode($data['authcode']))
                $this->ajaxReturn(['code' => 300, 'msg' => '验证码错误']);

            if(password($data['password'],$info['encrypt']) != $info['password'])
                $this->ajaxReturn(['code' => 300, 'msg' => '密码错误']);

            if(!$info['status'])
                $this->ajaxReturn(['code' => 300, 'msg' => '账户已被禁用']);

            //登录信息记录
            $passinfo = password($data['password']);

            $logininfo['password'] = $passinfo['password'];
            $logininfo['encrypt']  = $passinfo['encrypt'];
            $logininfo['nowtime']  = NOW_TIME;
            $logininfo['nowip']    = get_client_ip();
            $logininfo['loginnums'] = $info['loginnums'] + 1;
            $logininfo['lastip'] = $info['nowip'];
            $logininfo['lasttime'] = $info['nowtime'];

            if(!$logic::$model->where(['username'=>$data['username']])->setField($logininfo))
                $this->ajaxReturn(['code' => 300, 'msg' => '网络错误,请稍候再试!']);

            //记录session

            session('auid',$info['auid']);
            session('uid',$info['userid']);
            session('username',$data['username']);


            $this->ajaxReturn(['code' => 200, 'msg' => '登录成功','data'=>['url'=>U('Admin/Index/Index/index')]]);

        }else{

            session(null);
            $this->display();

        }
    }
    /**
     * Introduction: 验证验证码正确性
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @return bool
     */
    public function checkAuthcode($authcode)
    {
        $verify = new \Think\Verify();
        return $verify->check($authcode);
    }



    /**
     * @introduction: 验证码
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @return array
     */
    public function authcode()
    {

        $config = array(
            'fontSize'    =>    16,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'imageW'      =>    130,    // 验证码宽度
            'imageH'      =>    40     // 验证码高度
        );
        $authcode = new \Think\Verify($config);
        $authcode->entry();

    }
}