<?php
/**
 * User: arpeng
 * Date: 15/11/16
 * Time: 下午5:54
 * Last date: 15/11/16 下午5:54
 * Last user: arpeng
 */

namespace Common\Model\Admin;


use Think\Model;

class GroupAccessModel extends Model
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
        $this->connection = C('Administrator');
    }
}