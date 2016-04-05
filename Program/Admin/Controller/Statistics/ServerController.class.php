<?php
/**
 * @introduction: 统计服务器信息
 * @author: 杨陈鹏
 */
namespace Admin\Controller\Statistics;

use Common\Controller\Admin\CommonController;

class ServerController extends CommonController
{
    public function _initialize()
    {
        
    	

    }

	/**
     * @introduction: 统计服务器信息
     * @author: 杨陈鹏
     * @param string  $where  获取菜单的条件,只能为字符串
     * @param string  $fileds 需要获取的字段 
     * @return array
     */
	public function index()
	{
        
            include T();

	}
}