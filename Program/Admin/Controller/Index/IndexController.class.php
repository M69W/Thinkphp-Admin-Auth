<?php

/*
** @introduction: 后台首页
** @author: 杨陈鹏
** @date: 2015-03-25 20:06
** @email: yangchenpeng@cdlinglu.com
*/

namespace Admin\Controller\Index;

use Common\Controller\Admin\CommonController;
//use Think\Controller;

class IndexController extends CommonController
{

    /*
    ** @introduction: Root页面
    ** @author: 杨陈鹏
    ** @date: 2015-03-26 11:37
    ** @email: yangchenpeng@cdlinglu.com
    */
    public function index(){

        $aurhRuleLogic = D('AuthRule','Logic');

        //获取所以一级菜单
       $where = "pid = 0";//获取条件

       $fileds = 'id,pid,title,name,icon,child';//需要获取的字段

       $firstMenu = $aurhRuleLogic::getMenus($where,$fileds);

        foreach($firstMenu as $v){
            if($this->AUTH->check($v['name'],session('uid'))){//权限过滤
                $menu[] = $v;
            }
        }

        //如果什么权限都没有  则显示默认菜单
        if(!$menu){

            $menu[] = $aurhRuleLogic::getOneData(['id'=>C('DEFAULT_MENU_ID')],$fileds);
            $menu[0]['child'] = 0;

        }

        $this->firstMenu = $menu;

        $this->thisAdmin = $this->userinfo;
        $this->display();

    }
    /*
    ** @introduction: 首页
    ** @author: 杨陈鹏
    ** @date: 2015-03-26 11:37
    ** @email: yangchenpeng@cdlinglu.com
    */
    public function _index()
    {

        $this->display('Index/index/_index');

    }

    /*
    ** @introduction: 异步加载菜单
    ** @author: 杨陈鹏
    ** @date: 2015-03-26 13:15
    ** @email: yangchenpeng@cdlinglu.com
    */
    public function loadMenu()
    {

        //只能是Ajax请求
        !IS_AJAX || !IS_POST && $this->ajaxReturn(['code'=>300,'info'=>'非法请求']);

        $menuid = I('post.menuid') ? I('post.menuid') : 0 ;

        $level = I('post.level') ? I('post.level') : false ;

        !$level && $this->ajaxReturn(['code'=>300,'info'=>'请传入菜单等级参数']);

        $aurhRuleLogic = D('AuthRule','Logic');

        $fileds = 'id,pid,title,name,icon,child,cpath,ismenu';//需要获取的字段
        //如果是加载第三级菜单则需要把第四级菜单一起加载出来


        if($level == 2){

            //获取第三级ID
            $path = $aurhRuleLogic::getMenus('id = '.$menuid,'cpath');

            $cpath = explode(',', $path[0]['cpath']);

            unset($cpath[array_search($menuid, $cpath)]);

            $cpath = implode(',', $cpath);

            $where = 'id IN('.$cpath.')';


            //获取第三级数据
            $this->menu = $aurhRuleLogic::getMenus($where,$fileds);

            
            //获取第四级所有ID
            $cpath = '';

            foreach($this->menu as $k => $v){

                    $cpath .= ','.$v['cpath'];

            }

            //去除重复ID
            $cpath = $cpath.','.$path[0]['cpath'];

            $cpath = explode(',',$cpath);

            unset($cpath[array_search($menuid,$cpath)]);

            $cpath = implode(',',$cpath);

            $cpath = array_unique(explode(',', $cpath));

            $cpath = trim(implode(',', $cpath),',');

            $where = 'id IN('.$cpath.')';

        }else{

            $where = "pid = ".$menuid;

        }



        $menus = $aurhRuleLogic::getMenus($where,$fileds);

        foreach($menus as $v){
            if($this->AUTH->check($v['name'],session('uid'))){//权限过滤
                $menu[] = $v;
            }
        }

        $this->menu = $menu;

        switch ($level) {
            case 1:



                $this->display('Index/menu/second');

                break;
            case 2:


                $newarr = [];
                foreach($this->menu as $k => $v){
                        $newarr[$v['id']] = $v;

                }

                $this->menu = generateTree($newarr,'children');


                $this->display('Index/menu/third');

                break;
            default:

                $this->ajaxReturn(['code'=>300,'info'=>'请传入菜单等级参数']);

                break;
        }

    }


}