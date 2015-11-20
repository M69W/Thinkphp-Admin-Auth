<?php
/**
 * User: arpeng
 * Date: 15/11/14
 * Time: 下午12:20
 * Last date: 15/11/14 下午12:20
 * Last user: arpeng
 */

namespace Admin\Controller;

use Common\Controller\Admin\CommonController;

class RuleController extends CommonController
{

    protected $RuleLogic,$RuleModel;//定义权限模型
    public function _initialize()
    {
        $this->RuleLogic = D('AuthRule','Logic');
        $this->RuleModel = D('AuthRule','Model');
    }
    /**
     * Introduction: 权限规则首页
     * User: arpeng
     * Date: 15/11/14
     * Time: 08:57
     * Last date: 15/11/14 08:57
     * Last user: arpeng
     */
    public function index()
    {
        $this->redirect('Rule/lists');
    }
    /**
     * Introduction: 权限列表
     * User: arpeng
     * Date: 15/11/12
     * Time: 09:07
     * Last date: 15/11/14 09:07
     * Last user: arpeng
     */
    public function lists()
    {
        $pid = intval(I('get.iiii'));
        $lists = $this->RuleLogic->listsLogic($pid);
        if($pid){
            $ppid = $this->RuleLogic->where(['id'=>$pid])->getField('pid');
        }
        $this->TPL_Head();
        include T('lists');
        $this->TPL_Foot();
    }
    /**
     * Introduction: 新增权限规则
     * User: arpeng
     * Date: 15/11/12
     * Time: 09:08
     * Last date: 15/11/14 09:08
     * Last user: arpeng
     */
    public function create()
    {
        $RuleInfo = I('post.ruleinfo');
        if($RuleInfo){
            if(!$this->RuleModel->create($RuleInfo)){
                $this->error($this->RuleModel->getError());
            }else{
                $this->RuleModel->pid = $this->RuleModel->pid?$this->RuleModel->pid:0;
                $this->RuleModel->ismenu = $this->RuleModel->ismenu?$this->RuleModel->ismenu:0;
                $this->RuleModel->type = $this->RuleModel->type?$this->RuleModel->type:0;
                $id = $this->RuleModel->add();
                if($id){
                    $thisPathInfo['cpath'] = $id;
                    $thisPathInfo['ppath'] = $id;
                    if($RuleInfo != 0){//如果有父级ID //更新父级
                        $parentinfo = $this->RuleModel
                            ->where(['id'=>$RuleInfo['pid']])
                            ->field(['cpath','ppath'])
                            ->find();
                        $parentinfo['child'] = 1;
                        $parentinfo['cpath'] = $parentinfo['cpath'].','.$id;
                        $thisPathInfo['ppath'] = trim($parentinfo['ppath'].','.$id,',');
                        $this->RuleModel
                            ->where(['id'=>$RuleInfo['pid']])
                            ->setField($parentinfo);
                    }
                    $this->RuleModel
                        ->where(['id'=>$id])
                        ->setField($thisPathInfo);
                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }
        }else {
            $rules = $this->RuleModel->field('id,title,pid')->select();
            $tree = new \Org\Util\Tree();
            $tree->init($rules);
            $rules = $tree->create(0,'<option value=\'$id\'>$spacer $title</option>');
            $this->TPL_Head();
            include T('create');
            $this->TPL_Foot();
        }
    }
    /**
     * Introduction: 修改权限规则
     * User: arpeng
     * Date: 15/11/12
     * Time: 09:08
     * Last date: 15/11/14 09:08
     * Last user: arpeng
     */
    public function update()
    {
        $ruleinfo = I('post.ruleinfo');
        $id = I('get.iiii');
        if($ruleinfo){
            $pid = $ruleinfo['pid'];
            unset($ruleinfo['pid']);
            if(!$ruleinfo['ismenu'])
                $ruleinfo['ismenu'] = 0;
            if(!$ruleinfo['type'])
                $ruleinfo['type'] = 0;
            if($this->RuleModel->create($ruleinfo)){
                if($this->RuleModel->where(['id'=>$id])->save()){
                    $this->success('修改成功',U('Rule/lists',['iiii'=>$pid]));
                }else{
                    $this->error('修改失败');
                }
            }else{
                $this->error('修改失败');
            }
        }else{
            if(!$id)
                $this->error('参数错误');
            $info = $this->RuleLogic->where(['id'=>$id])->field('pid,title,name,type,ismenu,condition,status,icon')->find();
            $rules = $this->RuleModel->field('id,title,pid')->select();
            $tree = new \Org\Util\Tree();
            $tree->init($rules);
            $rules = $tree->create(0,'<option $selected value=\'$id\'>$spacer $title</option>',$info['pid']);
            $this->TPL_Head();
            include T('update');
            $this->TPL_Foot();
        }
    }
    /**
     * Introduction: 权限规则排序
     * User: arpeng
     * Date: 15/11/12
     * Time: 09:08
     * Last date: 15/11/14 09:08
     * Last user: Peter
     */
    public function sort()
    {
        if(!IS_POST)
            $this->error('非法请求!');
        $sorts = I('post.sort');
        foreach($sorts as $k=>$v){
            $this->RuleLogic->where(['id'=>$k])->setField(['sort'=>intval($v)]);
        }
        $this->success('排序成功!');
    }
}