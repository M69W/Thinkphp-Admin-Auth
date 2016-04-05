<?php
return array(

    'MODULE_ALLOW_LIST'    =>    array('Admin','Home','Files'),//运行访问模块

    'MODULE_DENY_LIST'     =>    array('Common','Runtime','Public'),//禁止访问模块

    'DEFAULT_MODULE'       =>    'Home',  // 默认模块

    'DEFAULT_THEME'    =>    'default', //设置模板主题为默认

    'URL_CASE_INSENSITIVE'  =>  true,//URL不区分大小写

    'TMPL_ENGINE_TYPE'      => 'PHP',//关闭模板引擎

    //    //开启路由
    'URL_ROUTER_ON'   => true,

    'DEFAULT_FILTER'  =>  'strip_tags,stripslashes',//输入过滤方式

    'LOAD_EXT_CONFIG' => 'database',//加载扩展配置

    'DEFAULT_CHARSET'       =>  'utf-8',//默认输出编码

    'SESSION_AUTO_START'    =>  true,    // 自动开启Session

    //路由配置
    'URL_MODEL' => 2,//URL模式

    'URL_CASE_INSENSITIVE' => true,//URL统一小写
    
);