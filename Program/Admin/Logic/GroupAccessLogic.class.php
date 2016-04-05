<?php

namespace Admin\Logic;

use Common\Model\Admin;

class GroupAccessLogic
{

    static public $model;

    public function __construct()
    {

        self::$model = new Admin\GroupAccessModel();//D('Common/Admin/GroupAccess');

    }

    /**
     * @introduction: 获取数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param string $where 查询条件
     * @param string $limit 分页
     * @param string $fileds 需要获取的字段
     * @return array
     */
    static public function getData($where = '', $fields = '*')
    {

        return self::$model
            ->where($where)
            ->order('uid asc')
            ->field($fields)
            ->select();

    }
    /**
     * @introduction: 插入数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param string $uid 查询条件
     * @param string $管理组ID 分页
     * @return array
     */
    static public function create($uid, $group_ids)
    {
        if(!$uid || !$group_ids)
            return ['code' => 300 , 'msg' => '参数错误'];

        if(!is_array($group_ids))
            $group_ids = explode(',',$group_ids);

        foreach($group_ids as $v){

            self::$model->add(['uid' => $uid,'group_id' => $v]);

        }

        return  ['code' => 200 , 'msg' => '添加成功!'];

    }
    /**
     * @introduction: 更新数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param string $uid 查询条件
     * @param string $管理组ID 分页
     * @return array
     */
    static public function update($uid, $group_ids)
    {

        if(!$uid || !$group_ids)
            return ['code' => 300 , 'msg' => '参数错误'];

        if(!is_array($group_ids))
            $group_ids = explode(',',$group_ids);

        if(self::$model->where(['uid' => $uid])->count()){

            self::$model->where(['uid' => $uid])->delete();

        }

        foreach($group_ids as $v){

            self::$model->add(['uid' => $uid,'group_id' => $v]);

        }

        return  ['code' => 200 , 'msg' => '修改成功!'];

    }
    /**
     * @introduction: 删除数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param string or array  删除条件
     * @return array
     */
    static public function delete($uid)
    {

        if(!$uid)
            return ['code' => 300 , 'msg' => '参数错误'];

        if(self::$model->where(['uid' => $uid])->delete()){

            return  ['code' => 200 , 'msg' => '删除成功!'];

        }else{

            return  ['code' => 300 , 'msg' => '删除失败!'];

        }

    }

}