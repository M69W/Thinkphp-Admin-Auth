<?php
/**
 * User: Peter
 * Date: 16/3/26
 * Time: 上午10:13
 * Last date: 16/3/26 上午10:13
 * Last user: Peter
 */

namespace Admin\Logic;

use Common\Model\Admin;

class CommonLogic
{

    public  $administrator,
            $authGroup,
            $authRule,
            $groupAccess;

    public function __construct()
    {

        $this->administrator = new Admin\AdministratorModel();//D('Common/Admin/Administrator');

        $this->authGroup = new Admin\AuthGroupModel();//D('Common/Admin/AuthGroup');

        $this->authRule = new Admin\AuthRuleModel();//D('Common/Admin/AuthRule');

        $this->groupAccess = new Admin\AuthGroupModel();//D('Common/Admin/GroupAccess');

    }

}