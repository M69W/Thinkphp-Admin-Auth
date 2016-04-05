<?php
return array(

    //Auth权限验证配置
    'AUTH_CONFIG'=>array(

        'AUTH_ON' => true, //认证开关

        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。

        'AUTH_GROUP' => 'auth_group', //用户组数据表名

        'AUTH_GROUP_ACCESS' => 'auth_group_access', //用户组明细表

        'AUTH_RULE' => 'auth_rule', //权限规则表

        'AUTH_USER' => 'administrator',//用户信息表

    ),

    'DEFAULT_MENU_ID' => 153,//管理员所有权限都没有时的默认显示菜单

    'NO_CHECK'         => [1],//无须验证UID

    //cookie配置
    'COOKIE_PREFIX'         => 'admin_', // cookie 名称前缀

    'COOKIE_EXPIRE'        =>  0,// cookie 保存时间

    'COOKIE_PATH'          =>  '/',// cookie 保存路径

    'COOKIE_DOMAIN'        => '', // cookie 有效域名

    'COOKIE_SECURE'        => true, //  cookie 启用安全传输
    
    'COOKIE_HTTPONLY'      => 1, // httponly设置

    'CONTROLLER_LEVEL'      =>  2, //定义多层控制器

    //静态路由规则
    'URL_MAP_RULES'=>array(
        // 'admin' => 'admin/index/index',
        // 'admin/index' => 'admin/index/index',
        // 'admin/basic' => 'admin/basic/index',
    ),
    // 更改PATHINFO参数分隔符
    'URL_PATHINFO_DEPR'=>'/',
    //静态路由规则
    'URL_ROUTE_RULES'=>array(

    ),

    //静态缓存
//    'HTML_CACHE_ON'     =>    true, // 开启静态缓存
//    'HTML_CACHE_TIME'   =>    600,   // 全局静态缓存有效期（秒）
//    'HTML_FILE_SUFFIX'  =>    '.html', // 设置静态缓存文件后缀
//    'HTML_CACHE_RULES'  =>     array(  // 定义静态缓存规则
//        // 定义格式1 数组方式
//        'index:index'   =>      array('index/index_{o}_{p}'),
//        'type:index'    =>     array('cat/cat_{uc}_{p}'),
//        'article:index'    =>     array('post/post_{au}'),
//        // 定义格式2 字符串方式
//        //'静态地址'    =>     '静态规则',
//    ),

    'TMPL_STRIP_SPACE'      =>  true,       // 是否去除模板文件里面的html空格与换行

    'TMPL_CACHE_ON'         =>  false,        // 是否开启模板编译缓存,

);