<?php
/**
 * User: arpeng
 * Date: 15/11/12
 * Time: 下午10:54
 * Last date: 15/11/12 下午10:54
 * Last user: arpeng
 */

namespace Common\Controller\Admin;


use Common\Controller\Admin;

class CommonController extends AuthController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Introduction: 公共头部
     * User: arpeng
     * Date: 15/11/12
     * Time: 下午9:59
     * Last date: 15/11/18 06:42
     * Last user: arpeng
     */
    public function TPL_Head(){
//        //获取当前会员所有规则
//        $groups = $this->AUTH->getGroups(session('uid'));
//        //获取规则ID
//        foreach($groups as $v)
//            $rules .= ','.$v['rules'];//提取ID
//        $rules = trim($rules,',');//去除两边逗号
//        $rules = array_unique(explode(',',$rules));//去除重复
//        $rules = implode(',',$rules);//生成逗号拼接的字符串
//
//        $AuthRule = M('auth_rule','',C('SKY_ADMIN'));
//        $menu = $AuthRule->where("id IN($rules) AND status=1 AND ismenu=1")
//                ->field('id,pid,title,name,cpath,ppath')
//                ->select();
//        //根据路径获取当前菜单信息
//        $name = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
//        $path = $AuthRule->where(['name'=>$name])->getField('ppath');
//        //获取菜单等级路径ID
//        $path = explode(',',$path);
//        if(count($path) < 3){//如果PATH个数小雨三个，则进行重定向
//            //获取二级菜单
//            if($menu)
//                foreach($menu as $v){
//                    if($v['pid'] == $path[0]){
//                        $secondMenu = $v;//获取第一个二级菜单
//                        break;
//                    }
//                };
//            if($secondMenu)
//                foreach($menu as $v){
//                    if($v['pid'] == $secondMenu['id']){
//                        $this->redirect($v['name']);//跳转
//                        break;
//                    }
//                };
//        }
        //获取当前会员所有规则
//        $groups = $this->AUTH->getGroups(session('uid'));
//        //获取规则ID
//        foreach($groups as $v)
//            $rules .= ','.$v['rules'];//提取ID
//        $rules = trim($rules,',');//去除两边逗号
//        $rules = array_unique(explode(',',$rules));//去除重复
//        $rules = implode(',',$rules);//生成逗号拼接的字符串

        $AuthRule = M('auth_rule','',C('SKY_ADMIN'));
        //获取所有菜单
        $menus = $AuthRule->where("status=1 AND ismenu=1")
                ->order('sort asc')
                ->field('id,pid,title,name,cpath,ppath,icon')
                ->select();
//        echo '<pre>';
//        var_dump($menus);
//        exit;
        foreach($menus as $v){
            if($this->AUTH->check($v['name'],session('uid'))){//权限过滤
                $menu[] = $v;
            }
        }
        //根据路径获取当前菜单信息
        $name = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
        $path = $AuthRule->where(['name'=>$name])->getField('ppath');
        //获取菜单等级路径ID
        $path = explode(',',$path);
        if(count($path) < 3){//如果PATH个数小于三个，则进行重定向
            //获取二级菜单
            if($menu)
                foreach($menu as $v){
                    if($v['pid'] == $path[0]){
                        $secondMenu = $v;//获取第一个二级菜单
                        break;
                    }
                };
            if($secondMenu)
                foreach($menu as $v){
                    if($v['pid'] == $secondMenu['id']){
                        $this->redirect($v['name']);//跳转
                        break;
                    }
                };
        }
        include T('Common/head');
    }
    /**
     * Introduction: 公共底部
     * User: arpeng
     * Date: 15/11/12
     * Time: 下午9:59
     * Last date: 15/11/12 下午9:59
     * Last user: arpeng
     */
    public function TPL_Foot(){
        include T('Common/foot');
    }
}