<?php
/**
 * Created by PhpStorm.
 * User: arpeng
 * Date: 15/11/10
 * Time: 下午4:48
 * 用户数据层模型
 */

namespace Common\Model\Admin;

use Think\Model;

class AdministratorModel extends Model
{
    //定义数据库连接信息
    protected $connection;
    //定义模型字段
    protected $fileds = ['userid','auid','username','password','encrypt','truename','email','mobile','remark','nowip','lastip','nowtime','loginnums','lasttime','regtime','nicname','regip','status','updatetime'];
    //定义表名
    protected $tableName = 'administrator';
    /**
     * 初始化
     */
    public function _initialize()
    {

        $this->connection = C('Administrator');

    }

}