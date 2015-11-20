<?php
    return array(
        //session_redis配置
        'SESSION' => array(
            'REDIS' => array(
                'SERVER' => '127.0.0.1',
                'PORT'   => 6300,
                'AUTH'   => 'sesion-#2015@11%11!',
                'SELECT' => 1,
                'PREFIX' => 'sky_admin'
            )
        ),
        //redis配置
        'REDIS' => array(
            'WRITE' => array(
                'REDIS_SERVER' => '127.0.0.1',
                'REDIS_PORT'   => 6379,
                'REDIS_AUTH'   => 'redis-#2015@11%11!',
                'REDIS_SELECT' => 0
            ),
            'GET'   => array(
                array(
                    'REDIS_SERVER' => '127.0.0.1',
                    'REDIS_PORT'   => 6379,
                    'REDIS_AUTH'   => 'redis-#2015@11%11!',
                    'REDIS_SELECT' => 0,
                ),
                array(
                    'REDIS_SERVER' => '127.0.0.1',
                    'REDIS_PORT'   => 6379,
                    'REDIS_AUTH'   => 'redis-#2015@11%11!',
                    'REDIS_SELECT' => 0,
                ),
                array(
                    'REDIS_SERVER' => '127.0.0.1',
                    'REDIS_PORT'   => 6379,
                    'REDIS_AUTH'   => 'redis-#2015@11%11!',
                    'REDIS_SELECT' => 0,
                ),
                array(
                    'REDIS_SERVER' => '127.0.0.1',
                    'REDIS_PORT'   => 6379,
                    'REDIS_AUTH'   => 'redis-#2015@11%11!',
                    'REDIS_SELECT' => 0,
                ),
            )
        ),
    );