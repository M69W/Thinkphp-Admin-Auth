<?php
/**
 * User: arpeng
 * Date: 15/11/15
 * Time: 下午1:06
 * Last date: 15/11/15 下午1:06
 * Last user: arpeng
 */

namespace Admin\Model;


use Think\Model;

class AuthGroupModel extends Model
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