<?php
/**
 * 对用户的密码进行加密
 * @param $password
 * @param $encrypt //传入加密串，在修改密码时做认证
 * @return array/password
 */
function password($password, $encrypt = '') {
    $pwd             = array();
    $pwd['encrypt']  = $encrypt?$encrypt:create_randomstr();
    $pwd['password'] = md5(md5(trim($password)).$pwd['encrypt']);
    return $encrypt?$pwd['password']:$pwd;
}
/**
 * 生成随机字符串
 * @param string $lenth 长度
 * @return string 字符串
 */
function create_randomstr($lenth = 6) {
    return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}
/**
 * 产生随机字符串
 *
 * @param    int        $length  输出长度
 * @param    string     $chars   可选的 ，默认为 0123456789
 * @return   string     字符串
 */
function random($length, $chars = '0123456789') {
    $hash = '';
    $max  = strlen($chars)-1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}
/**
 * Introduction: 生成无限极分类数组
 * User: arpeng
 * Date: 15/11/16
 * Time: 10:23
 * Last date: 15/11/16 10:23
 * Last user: arpeng
 * @param  array  $items 无限极分类一维数组
 * @param  $name  string 子级下标
 */
function generateTree($items, $name = 'child') {
    foreach($rule as $v)
        $rules[$v['id']] = $v;
    $tree = array();
    foreach($items as $item){
        if(isset($items[$item['pid']])){
            $items[$item['pid']][$name][] = &$items[$item['id']];
        }else{
            $tree[] = &$items[$item['id']];
        }
    }
    return $tree;
}