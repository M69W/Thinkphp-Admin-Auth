<?php
/**
 * Introduction: 管理员权限规则模型
 * User: arpeng
 * Date: 15/11/14
 * Time: 上午8:35
 * Last date: 15/11/14 上午8:35
 * Last user: arpeng
 */

namespace Admin\Logic;


use Think\Model;

class AuthRuleLogic extends Model
{
    //定义数据库连接信息
    protected $connection;
    //定义模型字段
    protected $fileds = ['id','name','ppath','cpath','title','type','status','condition','ismenu','pid','child','sort','icon'];
    //定义表名
    protected $tableName = 'auth_rule';
    /**
     * 初始化
     */
    public function _initialize()
    {
        $this->connection = C('SKY_ADMIN');
    }
    /**
     * Introduction: 权限列表
     * User: arpeng
     * Date: 15/11/12
     * Time: 09:07
     * Last date: 15/11/14 09:07
     * Last user: arpeng
     * @param int $pid 权限规则父级ID
     */
    public function listsLogic($pid)
    {
        $lists = $this->where(['pid'=>$pid])
                        ->order('sort asc')
                        ->field('id,title,name,condition,ismenu,status,child,sort')
                        ->select();
        return $lists;
    }
}