<?php
/**
 * User: arpeng
 * Date: 15/11/14
 * Time: 上午8:43
 * Last date: 15/11/14 上午8:43
 * Last user: arpeng
 */

namespace Admin\Logic;


use Think\Model;

class AuthGroupAccessLogic extends Model
{
    //定义数据库连接信息
    protected $connection;
    //定义模型字段
    protected $fileds = ['uid,group_id'];
    //定义表名
    protected $tableName = 'auth_group_access';
    /**
     * 初始化
     */
    public function _initialize()
    {
        $this->connection = C('SKY_ADMIN');
    }
}