<?php
/**
 * Introduction: 权限规则操作
 * @author: 杨陈鹏
 * @date: 2016/3/30 16:04
 * @email: yangchenpeng@cdlinglu.com
 */

namespace Admin\Controller\Basic;

use Common\Controller\Admin\CommonController;

class RuleController extends CommonController
{

    public function _initialize()
    {
        !IS_AJAX && exit('非法请求');
    }
    /**
     * Introduction: 权限列表
     * @author: 杨陈鹏
     * @date: 2016/3/30 16:04
     * @email: yangchenpeng@cdlinglu.com
     */
    public function lists()
    {
        //获取模型
        $authRuleLogic = D('AuthRule','Logic');

        //获取pid
        $this->pid = intval(I('post.pid'));


        $fileds = 'id,title,name,condition,ismenu,status,child,sort';

        $this->lists = $authRuleLogic::getData(['pid'=>$this->pid],'0,10000',$fileds);

        if($this->pid){

            $this->ppid = $authRuleLogic::$model->where(['id'=>$this->pid])->getField('pid');

        }

        //权限检测
        $this->authorization  = $this->AUTH;
        $this->display();

    }

    /**
     * @introduction: 获取菜单
     * @author: 杨陈鹏
     * @date: 2016/3/30 16:04
     * @email: yangchenpeng@cdlinglu.com
     */
    public function create()
    {

        //获取数据
        $data = I('post.data');

        //获取逻辑模型
        $authRuleLogic = D('AuthRule','Logic');


        if($data){

            //添加数据
            $result = $authRuleLogic::create($data);

            $this->ajaxReturn($result);

        }else {

            //获取所有规则
            $rules = $authRuleLogic::getData('','0,100000','id,title,pid');
            
            //获取树型下拉框
            $tree = new \Org\Util\Tree();

            $tree->init($rules);

            $this->rules = $tree->create(0,'<option value=\'$id\'>$spacer $title</option>');

        }

        $this->display();
    }
    /**
     * @introduction: 修改权限规则
     * @author: 杨陈鹏
     * @date: 2016/3/30 16:04
     * @email: yangchenpeng@cdlinglu.com
     */
    public function update()
    {
        //获取模型
         $logic = D('AuthRule','Logic');

        //获取数据
         $data = I('post.data');

         $this->pid = $pid = $data['pid'];

         $id = $data['id'];

         unset($data['pid']);
         unset($data['id']);

         if($data){

             if(!$data['ismenu'])
                 $data['ismenu'] = 0;

             if(!$data['type'])
                 $data['type'] = 0;

             if($result = $logic::update(['id'=>$id],$data)){

                 $result['data']['pid'] = $pid;

                 $this->ajaxReturn($result);

             }else{

                 $this->ajaxReturn(['code' => 300, 'msg' => '保存失败']);
             }

         }else{

             if(!$id)
                 $this->ajaxReturn(['code' => 300, 'msg' => '参数错误']);


             $this->info = $info = $logic::getOneData(['id'=>$id],'id,pid,title,name,type,ismenu,condition,status,icon');

             $rules = $logic::getData('','0,100000','id,title,pid');

             $tree = new \Org\Util\Tree();

             $tree->init($rules);

             $this->rules = $tree->create(0,'<option \$selected value=\'$id\'>$spacer $title</option>',$info['pid']);

             $this->display();

        }
    }

    /**
     * @introduction: 删除规则
     * @author: 杨陈鹏
     * @date: 2016/3/30 16:04
     * @email: yangchenpeng@cdlinglu.com
     */
    public function delete()
    {

        $logic = D('AuthRule','Logic');

        $where = I('post.data');

        $this->ajaxReturn($logic::delete($where));

    }

    /**
     * @introduction: 修改规则状态
     * @author: 杨陈鹏
     * @date: 2016/3/30 16:04
     * @email: yangchenpeng@cdlinglu.com
     */
    public function status()
    {

        $data = I('post.data');

        if(!is_array($data))
            $data = json_decode($data,true);

        $logic = D('AuthRule','Logic');

        if($logic::$model->where(['id'=>$data['id']])->setField(['status'=>$data['status']])){

            $this->ajaxReturn(['code' => 200,'data'=>$data]);

        }else{

            $this->ajaxReturn(['code' => 300,'msg'=>'网络错误,请稍后再试!']);

        }

    }

    /**
     * @introduction: 规则排序
     * @author: 杨陈鹏
     * @date: 2016/3/30 16:04
     * @email: yangchenpeng@cdlinglu.com
     */
    public function sort()
    {
         if(!IS_POST)
             $this->error('非法请求!');

         $logic = D('AuthRule','Logic');

         $sorts = I('post.data');


         foreach($sorts as $k=>$v){

             $logic::$model->where(['id'=>$k])->setField(['sort'=>intval($v)]);

         }


         $this->ajaxReturn(['code' => 200, 'msg' => '排序成功']);
    }
}