<?php
/**
 * 用户组逻辑层
 * User: arpeng
 * Date: 15/11/14
 * Time: 上午8:41
 * Last date: 15/11/14 上午8:41
 * Last user: arpeng
 */

namespace Admin\Logic;


use Think\Model;

class AuthGroupLogic extends Model
{
    protected $connection;//数据库连接
    //定义模型字段
    protected $fileds = ['id','title','status','rules','description','enname'];
    //定义表名
    protected $tableName = 'auth_group';
    public function _initialize(){
        $this->connection = C('SKY_ADMIN');
    }
}