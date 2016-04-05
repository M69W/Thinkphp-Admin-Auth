<?php
/**
 * @introduction: 规则或菜单逻辑类
 * @author: 杨陈鹏
 */
namespace Admin\Logic;

use Common\Model\Admin;

class AuthRuleLogic
{

    static public $model;

    public function __construct()
    {

        self::$model = new Admin\AuthRuleModel();//D('Common/Admin/AuthRule');

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
     * @introduction: 获取菜单
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param string  $where  获取菜单的条件,只能为字符串
     * @param string  $fileds 需要获取的字段
     * @return array
     */
    static public function getMenus($where, $fileds = '*')
    {

        if(!is_string($where) || !$where)
        {

            $where = "status=1 AND ismenu=1";

        }else{

            if(in_array(session('uid'),C('NO_CHECK'))){

                $where .= " AND ismenu=1";

            }else{

                $where .= " AND status=1 AND ismenu=1";

            }

        }

        $menus = self::$model
                ->where($where)
                ->order('sort asc')
                //->field('id,pid,title,name,cpath,ppath,icon')
                ->field($fileds)
                ->select();
        return $menus;
    }

    /**
     * @introduction: 添加数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param string  $data 录入数据
     * @return array
     */
    static public function create($data)
    {
        if(!$data)
            return ['code'=>300,'msg'=>'参数错误'];

            if(!self::$model->create($data)){

                self::$model->error(self::$model->getError());

            }else{

                //如果没有父级ID  则父级ID为0
                self::$model->pid = self::$model->pid ? self::$model->pid : 0 ;

                //检测是否为菜单  默认不是
                self::$model->ismenu = self::$model->ismenu ? self::$model->ismenu: 0 ;

                //插入数据
                $id = self::$model->add();

                if($id){

                    //添加子级路径
                    $thisPathInfo['cpath'] = $id;
                    
                    //更新父级路径
                    $thisPathInfo['ppath'] = $id;

                    if($data['pid'] != 0){
                    //如果有父级ID //更新父级

                        //获取父级子路径和父路径
                        $parentinfo = self::$model
                            ->where(['id'=>$data['pid']])
                            ->field(['cpath','ppath'])
                            ->find();

                        //更新父级是child字段
                        $parentinfo['child'] = 1;

                        //更新父级cpath字段
                        $parentinfo['cpath'] = $parentinfo['cpath'].','.$id;

                        //更新当前规则ppath路径
                        $thisPathInfo['ppath'] = trim($parentinfo['ppath'].','.$id,',');

                        //更新父级路径
                        self::$model
                            ->where(['id'=>$data['pid']])
                            ->setField($parentinfo);
                    }

                    //更新当前规则速记
                    self::$model
                        ->where(['id'=>$id])
                        ->setField($thisPathInfo);

                    return ['code'=>200,'msg'=>'添加成功'];

                }else{

                    return ['code'=>300,'msg'=>'添加失败'];

                }

            }
    }
    /**
     * @introduction: 获取数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param string  $where  查询条件
     * @param string  $limit  分页
     * @param string  $fileds 需要获取的字段
     * @return array
     */
    static public function getData($where = '', $limit = '0,20', $fields = '*')
    {

        return self::$model
                    ->where($where)
                    ->order('sort asc')
                    ->limit($limit)
                    ->field($fields)
                    ->select();

    }

    static public function delete($id)
    {
        if(!$id)
            return ['code' => 300, 'msg' => '参数错误'];

        //获取当前规则信息
        $thisInfo = self::$model
                ->where(['id'=>$id])
                ->field('pid,child')
                ->find();

        //如果有子级,禁止删除
        if($thisInfo['child'])
            return ['code' => 300, 'msg' => '当前规则有子级,禁止删除'];

        //获取当前规则父级ID
        $pid = $thisInfo['pid'];

        //删除操作
        if(self::$model->where(['id'=>$id])->delete()){

            //删除成功   如果有父，更新父级子路径
            if($pid){

                //获取子级路径
                $cpath = self::$model
                        ->where(['id'=>$pid])
                        ->getField('cpath');

                //分割成数组
                $cpath = explode(',',$cpath);

                //从数组里面删除当前ID
                unset($cpath[array_search($id,$cpath)]);

                //检测当前父级分类是否有子分类
                $isChild = self::$model
                                    ->where(['pid'=>$pid])
                                    ->limit('0,1')
                                    ->field('id')
                                    ->select();

                if(empty($isChild)){

                    //当前规则已经没有子集，修改child字段
                    $data['child'] = '0';

                }


                $cpath = implode(',',$cpath);



                $data['cpath'] = $cpath;

                self::$model->where(['id'=>$pid])->setField($data);

            }

            return ['code' => 200, 'msg' => '删除成功'];

        }else{

            return ['code' => 300, 'msg' => '删除失败'];

        }
    }
     /**
     * @introduction: 修改数据
     * @author: 杨陈鹏
     * @email: yangchenpeng@cdlinglu.com
     * @param string  $data 录入数据
     * @return array
     */
    static public function update($where, $data)
    {

        if(!$where || !$data)
            return ['code' => 300, 'msg' => '参数错误'];

        if(self::$model->where($where)->save($data)){

            return ['code' => 200, 'msg' => '保存成功'];

        }else{

            return ['code' => 300, 'msg' => '保存失败'];

        }

    }
}