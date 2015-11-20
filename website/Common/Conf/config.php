<?php
$basic = array(
    'MODULE_ALLOW_LIST'    =>    array('Home','Admin'),//运行访问模块
    'MODULE_DENY_LIST'     =>    array('Common','Runtime'),//禁止访问模块
    //'DEFAULT_MODULE'       =>    'Home',  // 默认模块

	'APP_SUB_DOMAIN_DEPLOY' =>  true,//开启域名部署
    'APP_SUB_DOMAIN_RULES'  =>  array(
        'admin' => 'Admin',//admin.skyclassroom.org  => Admin模块
        'www'   => 'Home', //www.skyclassroom.org && skyclassroom.org   => Home模块
    ),
    'URL_CASE_INSENSITIVE'  =>  true,//URL不区分大小写
    'TMPL_ENGINE_TYPE'      => 'PHP',//关闭模板引擎        //session_redis配置
    'URL_MODEL' => 2,//URL模式
    'URL_CASE_INSENSITIVE' => true,//URL统一小写
    'DEFAULT_FILTER'        =>  'strip_tags,stripslashes',//输入过滤方式
    'LOAD_EXT_CONFIG' => 'redis,mysql',//加载扩展配置

    'DEFAULT_CHARSET'       =>  'utf-8',//默认输出编码
);
return $basic;