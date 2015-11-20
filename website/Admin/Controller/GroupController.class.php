<?php
/**
 * User: arpeng
 * Date: 15/11/14
 * Time: 上午8:52
 * Last date: 15/11/14 上午8:52
 * Last user: arpeng
 */

namespace Admin\Controller;

use Common\Controller\Admin\CommonController;

class GroupController extends CommonController
{
    protected $GroupLogic,$GroupModel;//定义角色模型
    public function _initialize()
    {
        $this->GroupLogic = D('AuthGroup','Logic');
        $this->GroupModel = D('AuthGroup','Model');
    }
    /**
     * Introduction: 角色首页
     * User: arpeng
     * Date: 15/11/14
     * Time: 08:57
     * Last date: 15/11/14 08:57
     * Last user: arpeng
     */
    public function index()
    {
        $this->redirect('Group/lists');
    }
    /**
     * Introduction: 角色列表
     * User: arpeng
     * Date: 15/11/12
     * Time: 08:58
     * Last date: 15/11/14 08:58
     * Last user: arpeng
     */
    public function lists()
    {
        $lists = $this->GroupModel->select();
        $this->TPL_Head();
        include T('lists');
        $this->TPL_Foot();
    }
    /**
     * Introduction: 创建角色
     * User: arpeng
     * Date: 15/11/12
     * Time: 08:59
     * Last date: 15/11/14 08:59
     * Last user: arpeng
     */
    public function create()
    {
        $groupinfo = I('post.groupinfo');
        if($groupinfo){
            $groupinfo['rules'] = implode(',',$groupinfo['rules']);
            if($this->GroupModel->create($groupinfo)){
                if($this->GroupModel->add()){
                    $this->success('创建成功');
                }
            }else{
                $this->error('创建成功');
            }
        }else{
            $rule = D('AuthRule','Logic')->field('id,title,pid')->select();
            foreach($rule as $v)
                $rules[$v['id']] = $v;
            $rules = self::generateTree($rules);
            $ruleTree = self::getHtmlTree($rules);
            $this->TPL_Head();
            include T('create');
            $this->TPL_Foot();
        }
    }
    /**
     * Introduction: 生成无限极分类数组
     * User: arpeng
     * Date: 15/11/16
     * Time: 10:23
     * Last date: 15/11/16 10:23
     * Last user: arpeng
     */
    private function generateTree($items, $name = 'child') {
        $tree = array();
        foreach($items as $item){
            if(isset($items[$item['pid']])){
                $items[$item['pid']][$name][] = &$items[$item['id']];
            }else{
                $tree[] = &$items[$item['id']];
            }
        }
        return $tree;
//        foreach($items as $item)
//            $items[$item['pid']][$name][$item['id']] = &$items[$item['id']];
//        return isset($items[0][$name]) ? $items[0][$name] : array();
    }
    /**
     * Introduction: 生成无限极分类节点数
     * User: arpeng
     * Date: 15/11/16
     * Time: 10:23
     * Last date: 15/11/16 10:23
     * Last user: arpeng
     */
    private function getHtmlTree($items,$checked = array()){
        $str = '<ul>';
        foreach($items as $item){
            $check = '';
            if(in_array($item['id'],$checked))$check = 'checked';
            $str .= '<li><label class="block-rule"><input type="checkbox" '.$check.' name="groupinfo[rules][]" value="'.$item['id'].'">'.$item['title'].'</label>';
            if($item['child']){
                $str .= self::getHtmlTree($item['child'],$checked);
            }
            $str .= '</li>';
        }
        $str .= '</ul>';
        return $str;
    }
    /**
     * Introduction: 修改角色
     * User: arpeng
     * Date: 15/11/12
     * Time: 09:00
     * Last date: 15/11/14 09:00
     * Last user: arpeng
     */
    public function update()
    {
        $id = I('get.iiii');
        $groupinfo = I('post.groupinfo');
        if($groupinfo){
            $groupinfo['rules'] = implode(',',$groupinfo['rules']);
            $this->GroupLogic->create($groupinfo);
            if($this->GroupLogic->where(['id'=>$id])->save()){
                $this->success('修改成功',U('Group/lists'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $info = $this->GroupLogic->where(['id'=>$id])->find();
            $rule = D('AuthRule','Logic')->field('id,title,pid')->select();
            foreach($rule as $v)
                $rules[$v['id']] = $v;
            $rules = self::generateTree($rules);
            $ruleTree = self::getHtmlTree($rules,explode(',',$info['rules']));
            $this->TPL_Head();
            include T('update');
            $this->TPL_Foot();
        }
    }
    /**
     * Introduction: 删除角色
     * User: arpeng
     * Date: 15/11/12
     * Time: 09:00
     * Last date: 15/11/14 09:00
     * Last user: arpeng
     */
    public function delete()
    {

    }
    /**
     * Introduction: 角色授权
     * User: arpeng
     * Date: 15/11/12
     * Time: 09:00
     * Last date: 15/11/14 09:01
     * Last user: arpeng
     */
    public function authorization()
    {

    }
}