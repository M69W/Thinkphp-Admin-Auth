<?php
/**
 * Introduction: 管理员操作
 * @author: 杨陈鹏
 * @date: 2016/4/1 08:42
 * @email: yangchenpeng@cdlinglu.com
 */

namespace Admin\Controller\Basic;

use Common\Controller\Admin\CommonController;

class AdministratorController extends CommonController
{

    /**
     * Introduction: 用户组操作
     * @author: 杨陈鹏
     * @date: 2016/4/1 08:42
     * @email: yangchenpeng@cdlinglu.com
     */
    public function lists()
    {

        $logic = D('Administrator','Logic');

        $this->data = $logic::getData('','0,100000','');

        //权限检测
        $this->authorization  = $this->AUTH;
        $this->display();
    }

    /**
     * Introduction: 添加管理员
     * @author: 杨陈鹏
     * @date: 2016/4/1 08:42
     * @email: yangchenpeng@cdlinglu.com
     */
    public function create()
    {

        $data = I('post.data');

        if($data){

            $logic = D('Administrator','Logic');

            $this->ajaxReturn($logic::create($data));

        }else{

            $groupLogic = D('AuthGroup','Logic');

            $this->groups = $groupLogic::getData('','0,100000','id,title');

            $this->display();
        }

    }

    /**
     * Introduction: 修改管理员
     * @author: 杨陈鹏
     * @date: 2016/4/1 08:42
     * @email: yangchenpeng@cdlinglu.com
     */
    public function update()
    {

        $userid = I('post.userid');

        $data = I('post.data');

        if($data['userid']){
            $userid = $data['userid'];
            unset($data['userid']);
        }


        $logic = D('Administrator','Logic');

        if($data){

            $this->ajaxReturn($logic::update($userid,$data));


        }else{

            $this->data = $data = $logic::getOneData(['userid' => $userid]);

            $groupLogic = D('AuthGroup','Logic');

            $this->groups = $groupLogic::getData('','0,100000','id,title');

            $accessLogic = D('GroupAccess','Logic');

            $thisGroup = $accessLogic::getData(['uid' => $userid],'group_id');

            foreach($thisGroup as $k=>$v){

                $thisGroups[] = $v['group_id'];

            }
            $this->thisGroup = $thisGroups;

            $this->display();

        }

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

        $logic = D('Administrator','Logic');

        if($logic::$model->where(['userid'=>$data['userid']])->setField(['status'=>$data['status']])){

            $this->ajaxReturn(['code' => 200,'data'=>$data]);

        }else{

            $this->ajaxReturn(['code' => 300,'msg'=>'网络错误,请稍后再试!']);

        }

    }
    /**
     * Introduction: 删除管理员
     * @author: 杨陈鹏
     * @date: 2016/4/1 08:42
     * @email: yangchenpeng@cdlinglu.com
     */
    public function delete()
    {

        $userid = I('post.data');

        $logic = D('Administrator','Logic');

        $this->ajaxReturn($logic::delete($userid));


    }

    /**
     * Introduction: 管理员授权
     * @author: 杨陈鹏
     * @date: 2016/4/3 19:42
     * @email: yangchenpeng@cdlinglu.com
     */
    public function authorization()
    {

        $data = I('post.data');

        $userid = I('post.userid');

        if(!$userid)
            $userid = $data['userid'];

        if($data){

            $groupAccessLogic = D('GroupAccess','Logic');

            $this->ajaxReturn($groupAccessLogic::update($userid,$data['group']));


        }else{

            $groupLogic = D('AuthGroup','Logic');

            $this->groups = $groupLogic::getData('','0,100000','id,title');

            $accessLogic = D('GroupAccess','Logic');

            $thisGroup = $accessLogic::getData(['uid' => $userid],'group_id');

            foreach($thisGroup as $k=>$v){

                $thisGroups[] = $v['group_id'];

            }
            $this->userid = $userid;
            $this->thisGroup = $thisGroups;

            $this->display();

        }

    }

}