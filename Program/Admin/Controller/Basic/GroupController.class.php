<?php

/**
 * Introduction: 用户组操作
 * @author: 杨陈鹏
 * @date: 2016/3/31 08:42
 * @email: yangchenpeng@cdlinglu.com
 */

namespace Admin\Controller\Basic;

use Common\Controller\Admin\CommonController;

class GroupController extends CommonController
{

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
        //$lists = $this->GroupModel->select();
        $logic = D('AuthGroup','Logic');

        $this->data = $logic::getData('','0,100000');

        //权限检测
        $this->authorization  = $this->AUTH;
        $this->display();

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



        $authRule = D('AuthRule','Logic');

        $authGroup = D('AuthGroup','Logic');

         $data = I('post.data');

         if($data){

             $this->ajaxReturn($authGroup::create($data));

         }else{

             $rule = $authRule::getData('','0,100000','id,title,pid');

             foreach($rule as $v)

                 $rules[$v['id']] = $v;

             $rules = generateTree($rules);

             $this->ruleTree = self::getHtmlTree($rules);
            
            $this->display();

        }

    }

    /**
     * Introduction: 生成无限极分类节点数
     * User: arpeng
     * Date: 15/11/16
     * Time: 10:23
     * Last date: 15/11/16 10:23
     * Last user: arpeng
     */
    static public function getHtmlTree($items,$checked = array(),$level = 0){





        $str = '';

        $nums = count($items);

        if($level)
            $str .= '<div class="rules">';

        foreach($items as  $key => $item){

            if(!$level)
                $str .= '<div class="rules">';

            $check = '';

            if(in_array($item['id'],$checked))$check = 'checked="checked"';

            $str .= '<label class="check_box">
                        <input type="checkbox" pid="'.$item['pid'].'" id="'.$item['id'].'" '.$check.' value="'.$item['id'].'"> '.$item["title"].'
                    </label>';

            if($item['child']){


                $str .= self::getHtmlTree($item['child'],$checked,$level+1);

            }

            if(!$level)
                $str .= '</div>';
        }

        if($level)
            $str .= '</div>';

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
        $id = I('post.id');

        $data = I('post.data');

        $authRule = D('AuthRule','Logic');

        $logic = D('AuthGroup','Logic');

        if(!$id){

            $id = $data['id'];
            unset($data['id']);

            $this->ajaxReturn($logic::update(['id' => $id],$data));

        }else{

            $this->data = $data = $logic::getOneData(['id'=>$id]);

            $checked = explode(',', $data['rules']);

            $rule = $authRule::getData('','0,100000','id,title,pid');

            foreach($rule as $v)

                $rules[$v['id']] = $v;

            $rules = generateTree($rules);

            $this->ruleTree = self::getHtmlTree($rules, $checked);

            $this->display();

        }
    }
    /**
     * @introduction: 删除用户组
     * @author: 杨陈鹏
     * @date: 2016/3/30 16:04
     * @email: yangchenpeng@cdlinglu.com
     */
    public function delete()
    {

        $logic = D('AuthGroup','Logic');

        $where = I('post.data');

        $result = $logic::delete($where);

        $this->ajaxReturn($result);

    }

    /**
     * @introduction: 修改用户组状态
     * @author: 杨陈鹏
     * @date: 2016/3/30 16:04
     * @email: yangchenpeng@cdlinglu.com
     */
    public function status()
    {

        $data = I('post.data');

        if(!is_array($data))
            $data = json_decode($data,true);

        $logic = D('AuthGroup','Logic');

        if($logic::$model->where(['id'=>$data['id']])->setField(['status'=>$data['status']])){

            $this->ajaxReturn(['code' => 200,'data'=>$data]);

        }else{

            $this->ajaxReturn(['code' => 300,'msg'=>'网络错误,请稍后再试!']);

        }

    }
    
}