<?php

namespace Admin\Logic;

use Common\Model\Admin;

class AuthGroupLogic
{

    static public $model;

    public function __construct()
    {

        self::$model = new Admin\AuthGroupModel();//D('Common/Admin/AuthGroup');

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
    static public function getData($where = '', $limit = '0,20', $fields = '*')
    {

        return self::$model
            ->where($where)
            ->order('id asc')
            ->limit($limit)
            ->field($fields)
            ->select();

    }

    /**
     * @introduction: 获取一条数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param array or string $where 查询条件
     * @param $fileds 需要获取的字段
     * @return array
     */
    static public function getOneData($where = '', $fileds = '*')
    {

        return self::$model
                    ->where($where)
                    ->field($fileds)
                    ->find();

    }
    /**
     * @introduction: 插入数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param string $data 需要插入的数据
     * @return array
     */
    static public function create($data)
    {

        if(!$data['title'])
            return ['code' => 300, 'msg' => '标题不能为空!'];

        if($id = self::$model->add($data)){

            return ['code' => 200, 'msg' => '添加成功', 'data'=>['id'=>$id]];

        }else{

            return ['code' => 300, 'msg' => '插入失败'];

        }

    }
    /**
     * @introduction: 修改数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param string $where 条件
     * @param string $data 需要修改数据
     * @return array
     */
    static public function update($where, $data)
    {

        if(!$data['title'] || !$where)
            return ['code' => 300, 'msg' => '参数错误!'];


        if(self::$model->where($where)->save($data)){

            return ['code' => 200, 'msg' => '修改成功'];

        }else{

            return ['code' => 300, 'msg' => '修改失败'];

        }

    }
    /**
     * @introduction: 删除数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param array $where 条件
     * @param string $data 需要修改数据
     * @return array
     */
    static public function delete($where)
    {

        if(!$where['id'])
            return ['code' => 300, 'msg' => '参数错误'];

        $groupAccess = new Admin\GroupAccessModel();

        //查询当前管理组下面是否有管理员,如果有,禁止删除
        if($groupAccess->where(['group_id' => $where['id']])->count())
            return ['code' => 300, 'msg' => '当前管理组下有管理员,禁止删除'];

        if(self::$model->where($where)->delete()){

            return ['code' => 200, 'msg' => '删除成功'];

        }else{

            return ['code' => 300, 'msg' => '删除失败'];

        }


    }

}