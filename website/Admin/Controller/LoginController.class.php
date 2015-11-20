<?php
/**
 * Created by PhpStorm.
 * User: arpeng
 * Date: 15/11/10
 * Time: 下午6:37
 */

namespace Admin\Controller;


use Think\Controller;

class LoginController extends Controller
{
    public function _initialize()
    {

    }
    /**
     * Introduction: 登录页面
     * User: arpeng
     * Date: 15/11/12
     * Time: 22:08
     * Last date: 15/11/12 22:08
     * Last user: arpeng
     */
    public function index()
    {
        //清除记录
        Session('auid',null);
        Session('username',null);
        Session('uid',null);
        Cookie('username',null);
        include T('login');
    }


    /**
     * Introduction: 登录逻辑
     * User: arpeng
     * Date: 15/11/12
     * Time: 22:10
     * Last date: 15/11/12 22:10
     * Last user: arpeng
     */
    public function loginning(){
        $data['username'] = I('post.username');
        $data['password'] = I('post.password');
        $data['authcode'] = I('post.authcode');
        $Administrator = D('Administrator','Model');
        if(!$Administrator->create($data)){
            //登录失败   返回上一页
            $this->error($Administrator->getError());
        }else{
            //登录成功   将信息记录到session
            $auid = $Administrator
                ->where(['username'=>$data['username']])
                ->field('auid')
                ->find();
            //记录session
            Session('auid',$auid['auid']);
            Session('username',$data['username']);
            $userid = M('Administrator','',C('SKY_ADMIN'))->where(['username'=>strtolower($data['username'])])->getField('userid');
            Session('uid',$userid['userid']);
            //记录cookie
            Cookie('username',$data['username']);
            $this->success('登录成功',U('Index/index'));
        }

    }
    /**
     * Introduction: 验证码
     * User: arpeng
     * Date: 15/11/12
     * Time: 22:14
     * Last date: 15/11/12 22:14
     * Last user: arpeng
     */
    public function authCode(){
        $config = array(
            'fontSize'    =>    14,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'imageW'      =>    90,    // 验证码宽度
            'imageH'      =>    26     // 验证码高度
        );
        $authcode = new \Think\Verify($config);
        $authcode->entry();
    }
}