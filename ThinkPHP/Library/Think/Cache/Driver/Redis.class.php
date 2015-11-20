<?php
/*********************************************************************************
 * InitPHP 3.8.1 国产PHP开发框架   Dao-Nosql-Redis
 *-------------------------------------------------------------------------------
 * 版权所有: CopyRight By initphp.com
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 *-------------------------------------------------------------------------------
 * $Author:zhuli
 * $Dtime:2013-5-29
 ***********************************************************************************/
namespace Think\Cache\Driver;
use Think\Cache;
defined('THINK_PATH') or exit();
class Redis extends Cache {

	private $redis;//redis对象

	/**
	 * 写入数据  初始化Redis
	 * $config = array(
	 *  'server' => '127.0.0.1' 服务器
	 *  'port'   => '6379' 端口号
	 * )
	 * @param array $config
	 * @Notes  Peter 添加  		$redis->auth($config['auth']);
	$redis->select($config['select']);
	2015.5.1
	 */
	public function set($config = array()) {
		$configs = C('REDIS.WRITE');
		if (!$config['REDIS_SERVER']) {
			$config['REDIS_SERVER'] = $configs['REDIS_SERVER'];
		}

		if (!$config['REDIS_PORT']) {
			$config['REDIS_PORT'] = $configs['REDIS_PORT'];
		}

		if (!$config['REDIS_AUTH']) {
			$config['REDIS_AUTH'] = $configs['REDIS_AUTH'];
		}

		if (!$config['REDIS_SELECT']) {
			$config['REDIS_SELECT'] = $configs['REDIS_SELECT'];
		}

		$this->redis = new \Redis();
		$this->redis->connect($config['REDIS_SERVER'], $config['REDIS_PORT']);
		$this->redis->auth($config['REDIS_AUTH']);
		$this->redis->select($config['REDIS_SELECT']);
		return $this->redis;
	}
	/**
	 * 读取数据  初始化Redis
	 * $config = array(
	 *  'server' => '127.0.0.1' 服务器
	 *  'port'   => '6379' 端口号
	 * )
	 * @param array $config
	 * @Notes  Peter 添加  		$redis->auth($config['auth']);
	$redis->select($config['select']);
	2015.5.1
	 */
	public function get($c = array()){
		$configs = C('REDIS.GET');
		$max = count($configs) -1;
		$dbNum = rand(0,$max);
		$conf = $configs[$dbNum];
		$config['REDIS_SERVER'] = $c['REDIS_SERVER']?$c['REDIS_SERVER']:$conf['REDIS_SERVER'];
		$config['REDIS_PORT'] = $c['REDIS_PORT']?$c['REDIS_PORT']:$conf['REDIS_PORT'];
		$config['REDIS_AUTH'] = $c['REDIS_AUTH']?$c['REDIS_AUTH']:$conf['REDIS_AUTH'];
		$config['REDIS_SELECT'] = $c['REDIS_SELECT']?$c['REDIS_SELECT']:$conf['REDIS_SELECT'];

		$this->redis = new \Redis();
		$this->redis->connect($config['REDIS_SERVER'], $config['REDIS_PORT']);
		$this->redis->auth($config['REDIS_AUTH']);
		$this->redis->select($config['REDIS_SELECT']);
		return $this->redis;
	}
}