<?php
/**
 * Created by PhpStorm.
 * User: arpeng
 * Date: 15/11/10
 * Time: 下午4:48
 * 用户数据层模型
 */

namespace Admin\Model;

use Think\Model;

class AdministratorModel extends Model
{
    //定义数据库连接信息
    protected $connection;
    //定义模型字段
    protected $fileds = ['userid','auid','username','password','encrypt','truename','email','mobile','remark','nowip','lastip','nowtime','loginnums','lasttime','regtime','nicname','regip','status'];
    //定义表名
    protected $tableName = 'administrator';
    //登录自动验证规则
    protected $_validate = array(
        ['authcode','require','验证码不能为空!',1],
        ['username','require','用户名不能为空!',1],
        ['username','checkStatus','您的账户已被禁用!',1,'callback',3],
        ['password','require','密码不能为空!',1],
        ['authcode','checkAuthcode','验证码错误!',1,'callback',3],
        ['password','checkPwd','密码错误!',1,'callback',3],
    );
    /**
     * 初始化
     */
    public function _initialize()
    {
        $this->connection = C('SKY_ADMIN');
    }
    /**
     * Introduction: 验证验证码正确性
     * User: arpeng
     * Date: 15/11/12
     * Time: 22:16
     * Last date: 15/11/12 22:16
     * Last user: arpeng
     */
    public function checkAuthcode($authcode)
    {
        $verify = new \Think\Verify();
        return $verify->check($authcode);
    }
    /**
     * Introduction: 验证密码正确性
     * User: arpeng
     * Date: 15/11/12
     * Time: 22:16
     * Last date: 15/11/12 22:17
     * Last user: arpeng
     */
    public function checkPwd($password)
    {
        //获取数据库密码和加密因子
        $userinfo = $this->where(['username' => I('post.username')])->field('password,encrypt,nowip,nowtime,loginnums')->find();
        if(md5(md5(strtolower($password)).$userinfo['encrypt']) === $userinfo['password']){
            //更新密码和加密因子
            $data = password($password);//更新密码和密码加密因子
            $data['nowip'] = get_client_ip();//更新当前登录IP
            $data['nowtime'] = NOW_TIME;//更新当前登录时间
            $data['lastip'] = $userinfo['nowip'];//更新上次登录IP
            $data['lasttime'] = $userinfo['nowtime'];//更新上传登录时间
            $data['loginnums'] = $userinfo['loginnums'] + 1;//更新登录次数
            $this->where(['username' => strtolower(I('post.username'))])->setField($data);//更新数据
            return true;
        }else{
            return false;
        }
    }
    /**
     * Introduction: 判断账户是否被禁用
     * User: arpeng
     * Date: 15/11/12
     * Time: 22:16
     * Last date: 15/11/12 22:17
     * Last user: arpeng
     */
    public function checkStatus($username)
    {
        $status = $this->where(['username'=>$username])->getField('status');
        return boolval($status['status']);

    }

}