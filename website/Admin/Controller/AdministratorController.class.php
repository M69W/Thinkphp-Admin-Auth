<?php
/**
 * Introduction: 管理员操作控制器类
 * User: arpeng
 * Date: 15/11/12
 * Time: 下午9:59
 * Last date: 15/11/12 下午9:59
 * Last user: arpeng
 */

namespace Admin\Controller;


use Common\Controller\Admin\CommonController;

class AdministratorController extends CommonController
{
    protected $Administrator;
    public function _initialize()
    {
        $this->Administrator = M('Administrator','',C('SKY_ADMIN'));
    }
    /**
     * Introduction: 管理员管理首页
     * User: arpeng
     * Date: 15/11/12
     * Time: 下午9:59
     * Last date: 15/11/12 下午9:59
     * Last user: arpeng
     */
    public function index()
    {
//        $this->redirect('Administrator/lists');
          $this->TPL_Head();
          $this->TPL_Foot();
    }
    /**
     * Introduction: 管理员列表
     * User: arpeng
     * Date: 15/11/12
     * Time: 下午9:59
     * Last date: 15/11/12 下午9:59
     * Last user: arpeng
     */
    public function lists()
    {
        $lists = $this->Administrator
            ->order('userid asc')
            ->field('
                userid,
                username,
                auid,
                truename,
                nicname,
                mobile,
                nowtime,
                email,
                nowip,
                regip,
                regtime,
                status,
                loginnums')
            ->select();
        $this->TPL_Head();
        include T('lists');
        $this->TPL_Foot();
    }
    /**
     * Introduction: 创建管理员
     * User: arpeng
     * Date: 15/11/12
     * Time: 下午9:59
     * Last date: 15/11/12 下午9:59
     * Last user: arpeng
     */
    public function create()
    {
        $userinfo = I('post.userinfo');
        $group = I('post.group');
        if($userinfo){
            //自动验证规则
            $rules = [
                ['username','|^[a-zA-Z0-9_]{3,}$|','用户名格式不正确',1,'regex'],
                ['password','|(?!^\\d+$)(?!^[a-zA-Z]+$){6,}$|','密码格式不正确',1,'regex'],
                ['truename','require','中文名不能为空',1],
                ['mobile','|\d+|','手机号码格式不正确',1,'regex'],
                ['email','email','邮箱格式不正确',1],
            ];
            if(!$group){
                $this->error('请至少选择一个角色');
            }
            if($this->Administrator->where(['username'=>$userinfo['username']])->find())
                $this->error('用户名已经存在');
            if($this->Administrator->where(['mobile'=>$userinfo['mobile']])->find())
                $this->error('手机号码已经存在');
            if($this->Administrator->where(['email'=>$userinfo['email']])->find())
                $this->error('邮箱已存在');
            if(!$this->Administrator->validate($rules)->create($userinfo)){
                $this->error($this->Administrator->getError());
            }else{
                //密码加密
                $passinfo = password(strtolower($this->Administrator->password));
                $this->Administrator->password = $passinfo['password'];
                $this->Administrator->username = strtolower($this->Administrator->username);
                $this->Administrator->encrypt = $passinfo['encrypt'];
                $this->Administrator->regtime = NOW_TIME;
                $this->Administrator->auid = md5(($this->Administrator->username).$this->Administrator->regtime);
                $this->Administrator->regip = get_client_ip();
                $this->Administrator->nicname = $this->Administrator->nicname?$this->Administrator->nicname:$this->Administrator->username;
                if($userid = $this->Administrator->add()){
                    $access = D('AuthGroupAccess','Logic');
                    $data['uid'] = $userid;
                    foreach($group as $v){
                        $data['group_id'] = $v;
                        $access->data($data)->add();
                    }
                    $this->success('创建成功',U());
                }else{
                    $this->error('创建失败');
                }
            }
        }else{
            //获取角色列表
            $groups = D('AuthGroup','Logic')->field('id,title')->select();
            $this->TPL_Head();
            include T('create');
            $this->TPL_Foot();
        }
    }
    /**
     * Introduction: 修改管理员
     * User: arpeng
     * Date: 15/11/12
     * Time: 下午9:59
     * Last date: 15/11/12 下午9:59
     * Last user: arpeng
     */
    public function update()
    {
        $userinfo = array_filter(I('post.userinfo'));
        $auid = I('get.iiii');
        if(IS_POST){
            //自动验证规则
            unset($userinfo['username']);
            $rules = [
                //['username','|^[a-zA-Z0-9_]{3,}$|','用户名格式不正确',1,'regex'],
                ['password','|(?!^\\d+$)(?!^[a-zA-Z]+$){6,}$|','密码格式不正确',2,'regex'],
                ['truename','require','中文名不能为空',1],
                ['mobile','|\d+|','手机号码格式不正确',1,'regex'],
                ['email','email','邮箱格式不正确',1],
            ];
            if($userinfo['password']){
                $pwdinfo = password(strtolower($userinfo['password']));
                $userinfo['password'] = $pwdinfo['password'];
                $userinfo['encrypt'] = $pwdinfo['encrypt'];
            }
            if(!$this->Administrator->validate($rules)->create($userinfo)){
                $this->error($this->Administrator->getError());
            }else{
                if($this->Administrator->where(['auid'=>$auid])->save()){
                    $this->success('修改成功');
                }else{
                    $this->error('修改失败,请至少修改一项!授权除外');
                }
            }
        }else{
            if(!$auid)
                $this->error('参数错误');
            $info = $this->Administrator->where(['auid'=>$auid])->field('userid,username,truename,mobile,email,nicname,remark')->find();
            $this->TPL_Head();
            include T('update');
            $this->TPL_Foot();
        }
    }
    /**
     * Introduction: 管理员授权
     * User: arpeng
     * Date: 15/11/12
     * Time: 下午9:59
     * Last date: 15/11/12 下午9:59
     * Last user: arpeng
     */
    public function authorization(){
        $auid = I('get.iiii');
        $group = I('post.group');
        $userid = $this->Administrator->where(['auid'=>$auid])->getField('userid');
        if(IS_POST){
            if(!$group)
                $this->error('请至少选择一个角色');
            $access = D('AuthGroupAccess','Logic');
            $access->where(['uid'=>$userid])->delete();
            $data['uid'] = $userid;
            foreach($group as $v){
                $data['group_id'] = $v;
                $access->data($data)->add();
            }
            $this->success('授权成功');
        }else{
            $groupss = $this->AUTH->getGroups($userid);//获取当前会员权限
            foreach($groupss as $v){
                $groupids[] = $v['group_id'];
            }
            //获取角色列表
            $groups = D('AuthGroup','Logic')->field('id,title')->select();//获取所有权限
            $this->TPL_Head();
            include T('authorization');
            $this->TPL_Foot();
        }
    }

}