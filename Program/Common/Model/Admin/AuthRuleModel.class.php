<?php
/**
 * User: arpeng
 * Date: 15/11/14
 * Time: 下午12:28
 * Last date: 15/11/14 下午12:28
 * Last user: arpeng
 */

namespace Common\Model\Admin;


use Think\Model;

class AuthRuleModel extends Model
{
    //定义数据库连接信息
    protected $connection;
    //定义模型字段
    protected $fileds = ['id','name','ppath','cpath','title','type','status','condition','ismenu','pid','child','sort','icon'];
    //定义表名
    protected $tableName = 'auth_rule';
    //自动验证规则
//    protected $_validate = array(
//        ['title','|[\u4e00-\u9fa5a-zA-Z]+|','规则名称只能是中文或者英文!',1,'regex'],
//        ['name','|[A-Za-z]+\\[A-Za-z]+\\[A-Za-z]+|','规则标识格式不对!',1,'regex'],
//        ['url','|[A-Za-z]+\\[A-Za-z]+|','URL格式不对!',1,'regex'],
//    );
    /**
     * 初始化
     */
    public function _initialize()
    {
        $this->connection = C('Administrator');
    }
}