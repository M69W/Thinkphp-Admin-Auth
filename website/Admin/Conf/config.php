<?php
$basic = array(
            'DEFAULT_THEME' => 'default',//开启模板主题
            'STATIC_PATH'   => 'http://statics.skyclassroom.com/admin/',
            'STATIC_COMMON_PATH' => 'http://statics.skyclassroom.com/common/',
            'AUTH_CONFIG'=>array(
                'AUTH_ON' => true, //认证开关
                'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
                'AUTH_GROUP' => 'auth_group', //用户组数据表名
                'AUTH_GROUP_ACCESS' => 'auth_group_access', //用户组明细表
                'AUTH_RULE' => 'auth_rule', //权限规则表
                'AUTH_USER' => 'administrator',//用户信息表
            ),
            'NO_CHECK'         => [1],//无须验证UID

            'SHOW_PAGE_TRACE' =>true,//开启页面trace    //session redis驱动配置
            //session redis 配置
            'SESSION_AUTO_START'    =>  true,    // 是否自动开启Session
            'SESSION_TYPE'          =>  'Redis',    //session类型
            'SESSION_PERSISTENT'    =>  1,        //是否长连接(对于php来说0和1都一样)
            'SESSION_CACHE_TIME'    =>  1,        //连接超时时间(秒)
            'SESSION_EXPIRE'        =>  1200,        //session有效期(单位:秒) 0表示永久缓存
            'SESSION_PREFIX'        =>  'sky_admin:',        //session前缀
            'SESSION_REDIS_HOST'    =>  '127.0.0.1,127.0.0.1', //分布式Redis,默认第一个为主服务器
            'SESSION_REDIS_PORT'    =>  '6300',           //端口,如果相同只填一个,用英文逗号分隔
            'SESSION_REDIS_AUTH'    =>  'session-#2015@11%11!',//redis密码
            //cookie配置
            'COOKIE_PREFIX'         => 'sky_admin:', // cookie 名称前缀
            'COOKIE_EXPIRE'        =>  0,// cookie 保存时间
            'COOKIE_PATH'          =>  '/',// cookie 保存路径
            'COOKIE_DOMAIN'        => 'admin.skyclassroom.com', // cookie 有效域名
            'COOKIE_SECURE'        => true, //  cookie 启用安全传输
            'COOKIE_HTTPONLY'      => 1, // httponly设置
            //路由配置
        );
return $basic;