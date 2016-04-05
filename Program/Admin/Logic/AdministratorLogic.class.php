<?php

namespace Admin\Logic;

use Common\Model\Admin;

class AdministratorLogic
{

    static public $model;

    public function __construct()
    {

        self::$model = new Admin\AdministratorModel();//D('Common/Model/Admin/Administrator');

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
            ->order('userid asc')
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

        if(!$data)
            return ['code' => 300, 'msg' =>'参数错误'];

        $group = $data['group'] ;

        unset($data['group']);

        if(!is_array($group))
            $group = explode(',',$group);

        if(!$group)
            return ['code' => 300, 'msg' =>'请至少选择一个角色'];

        //自动验证规则
        $rules = [
            ['username','|^[a-zA-Z0-9_]{3,}$|','用户名格式不正确',1,'regex'],
            ['password','|(?!^\\d+$)(?!^[a-zA-Z]+$){6,}$|','密码格式不正确',1,'regex'],
            ['truename','require','中文名不能为空',1],
            ['mobile','|\d+|','手机号码格式不正确',1,'regex'],
            ['email','email','邮箱格式不正确',1],
        ];


        if(!self::$model->validate($rules)->create($data))
            return ['code' => 300, 'msg' =>self::$model->getError()];

        if(self::$model->where(['username' => self::$model->username])->find())
            return ['code' => 300, 'msg' =>'用户名以存在'];

        if(self::$model->where(['mobile'=> self::$model->mobile ])->find())
            return ['code' => 300, 'msg' =>'手机号码已存在'];

        if(self::$model->where(['email'=> self::$model->email ])->find())
            return ['code' => 300, 'msg' =>'邮箱已经存在'];


        //密码加密
        $passinfo = password(strtolower(self::$model->password));

        self::$model->password = $passinfo['password'];

        self::$model->username = strtolower(self::$model->username);

        self::$model->encrypt = $passinfo['encrypt'];

        self::$model->regtime = NOW_TIME;

        self::$model->updatetime = NOW_TIME;

        self::$model->auid = md5((self::$model->username).self::$model->regtime);

        self::$model->regip = get_client_ip();

        self::$model->nicname = self::$model->nicname?self::$model->nicname:self::$model->username;

        if($userid = self::$model->add()){

            $access = D('GroupAccess','Logic');//new Admin\GroupAccessModel();//D('AuthGroupAccess','Logic');

            $access::create($userid,$group);

            return ['code' => 200, 'msg' =>'创建成功'];

        }else{

            return ['code' => 300, 'msg' =>'创建失败'];

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
    static public function update($userid, $data)
    {

        if(!$data && !$userid)
            return ['code' => 300, 'msg' =>'参数错误'];

        $group = $data['group'] ;

        unset($data['group']);

        unset($data['username']);

        if(!is_array($group))
            $group = explode(',',$group);

        if(!$group)
            return ['code' => 300, 'msg' =>'请至少选择一个角色'];

        //自动验证规则
        $rules = [
            ['truename','require','中文名不能为空',1],
            ['mobile','|\d+|','手机号码格式不正确',1,'regex'],
            ['email','email','邮箱格式不正确',1],
        ];

        //如果有密码则验证密码

        if($data['password']){
            array_push($rules,['password','|(?!^\\d+$)(?!^[a-zA-Z]+$){6,}$|','密码格式不正确',1,'regex']);
        }

        if(!self::$model->validate($rules)->create($data)){

            return ['code' => 300, 'msg' =>self::$model->getError()];

        }else{

            if($data['password']){
                //密码加密
                $passinfo = password(strtolower(self::$model->password));

                self::$model->password = $passinfo['password'];

                self::$model->encrypt = $passinfo['encrypt'];

            }

            self::$model->updatetime = NOW_TIME;
            self::$model->nicname = self::$model->nicname?self::$model->nicname:self::$model->username;


            if(self::$model->where(['userid' => $userid])->save()){

                $access = D('GroupAccess','Logic');//new Admin\GroupAccessModel();//D('AuthGroupAccess','Logic');


                $access::update($userid,$group);

                return ['code' => 200, 'msg' =>'修改成功'];

            }else{

                return ['code' => 300, 'msg' =>'修改失败'];

            }
        }

    }
    /**
     * @introduction: 删除数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param array $where 条件
     * @return array
     */
    static public function delete($uid)
    {

        if(!$uid)
            return ['code' => 300, 'msg' => '参数错误'];

        if(self::$model->where(['userid' => $uid])->delete()){

            //删除授权
            $groupAcccessLogic = D('GroupAccess','Logic');

            $groupAcccessLogic::delete($uid);

            return ['code' => 200, 'msg' => '删除成功'];

        }else{

            return ['code' => 300, 'msg' => '删除失败'];

        }

    }

}