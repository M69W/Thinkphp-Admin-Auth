<?php
/**
 * Created by PhpStorm.
 * User: arpeng
 * Date: 15/11/10
 * Time: 下午4:49
 * 用户服务层模型类
 */

namespace Admin\Service;


class AdministratorService extends \Think\Model
{
    protected $connection;//数据库连接
    //定义模型字段
    protected $fileds = ['userid','auid','username','password','encrypt','truename','email','mobile','remark','nowip','lastip','nowtime','loginnums','lasttime','regtime','nicname','regip','status'];
    //定义表名
    protected $tableName = 'administrator';
    public function _initialize(){
        $this->connection = C('SKY_ADMIN');
    }

}