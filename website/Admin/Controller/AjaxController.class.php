<?php
/**
 * User: arpeng
 * Date: 15/11/15
 * Time: 下午12:38
 * Last date: 15/11/15 下午12:38
 * Last user: arpeng
 */

namespace Admin\Controller;

use Common\Controller\Admin\CommonController;

class AjaxController extends CommonController
{
    protected $administrator,$rule;
    public function _initialize(){
        !IS_AJAX && $this->error('非法访问');
        $this->administrator = D('Administrator','Logic');
        $this->rule = D('AuthRule','Logic');
    }
    /**
     * Introduction: 删除权限规则
     * User: arpeng
     * Date: 15/11/12
     * Time: 09:09
     * Last date: 15/11/14 09:09
     * Last user: arpeng
     */
    public function ruleDelete()
    {
        $id = I('post.iiii');
        $pid = $this->rule->where(['id'=>$id])->getField('pid');
        if($this->rule->where(['id'=>$id])->delete()){
            //删除成功   更新父级子路径
            if($pid){
                $cpath = $this->rule->where(['id'=>$pid])->getField('cpath');
                $cpath = explode(',',$cpath);
                unset($cpath[array_search($id,$cpath)]);
                $cpath = implode(',',$cpath);
                $this->rule->where(['id'=>$pid])->setField(['cpath'=>$cpath]);
            }
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(0);
        }
    }
    /**
     * Introduction: 权限规则状态操作
     * User: arpeng
     * Date: 15/11/15
     * Time: 12:00
     * Last date: 15/11/15 12:00
     * Last user: arpeng
     */
    public function ruleStatus()
    {
        $status = I('post.iii');
        $id = I('post.iiii');
        if($this->rule->where(['id'=>$id])->setField(['status'=>$status])){
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(0);
        }
    }
    /**
     * Introduction: 删除管理员
     * User: arpeng
     * Date: 15/11/12
     * Time: 下午9:59
     * Last date: 15/11/12 下午9:59
     * Last user: arpeng
     */
    public function administratorDelete()
    {
        $auid = I('post.iiii');
        if(!$auid || strlen($auid) != 32){
            $this->ajaxReturn('0');
        }
        if($this->administrator->where(['auid'=>$auid])->delete()){
            $this->ajaxReturn('1');
            exit;
        }else{
            $this->ajaxReturn('0');
            exit;
        }
    }
    /**
     * Introduction: 更新管理员状态
     * User: arpeng
     * Date: 15/11/13
     * Time: 20:23
     * Last date: 15/11/13 20:23
     * Last user: arpeng
     */
    public function administratorStatus()
    {
        $auid = I('post.iiii');
        $status = I('post.iii');
        if(!$auid || strlen($auid) != 32){
            $this->ajaxReturn('0');
        }
        if($this->administrator->where(['auid'=>$auid])->setField(['status'=>$status])){
            $this->ajaxReturn('1');
            exit;
        }else{
            $this->ajaxReturn('0');
            exit;
        }
    }
    /**
     * Introduction: 用户组状态操作
     * User: arpeng
     * Date: 15/11/15
     * Time: 12:00
     * Last date: 15/11/15 12:00
     * Last user: arpeng
     */
    public function groupStatus()
    {
        $group = D('AuthGroup','Logic');
        $status = I('post.iii');
        $id = I('post.iiii');
        if($group->where(['id'=>$id])->setField(['status'=>$status])){
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(0);
        }
    }
    /**
     * Introduction: 删除用户组
     * User: arpeng
     * Date: 15/11/15
     * Time: 12:00
     * Last date: 15/11/15 12:00
     * Last user: arpeng
     */
    public function groupDelete()
    {
        $group = D('AuthGroup','Model');
        $id = I('post.iiii');
        if($group->where(['id'=>$id])->delete()){
            $this->ajaxReturn('1');
            exit;
        }else{
            $this->ajaxReturn('0');
            exit;
        }
    }
}