<?php
/**
 * 将字符串转换为数组
 *
 * @param	string	$data	字符串
 * @return	array	返回数组格式，如果，data为空，则返回空数组
 */
function string2array($data) {
    $data = trim($data);
    if($data == '') return array();
    if(strpos($data, 'array')===0){
        @eval("\$array = $data;");
    }else{
        if(strpos($data, '{\\')===0) $data = stripslashes($data);
        $array=json_decode($data,true);
        if(strtolower(CHARSET)=='gbk'){
            $array = mult_iconv("UTF-8", "GBK//IGNORE", $array);
        }
    }
    return $array;
}
/**
 * 将数组转换为字符串
 *
 * @param	array	$data		数组
 * @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
 * @return	string	返回字符串，如果，data为空，则返回空
 */
function array2string($data, $isformdata = 1) {
    if($data == '' || empty($data)) return '';

    if($isformdata) $data = new_stripslashes($data);
    if(strtolower(CHARSET)=='gbk'){
        $data = mult_iconv("GBK", "UTF-8", $data);
    }
    if (version_compare(PHP_VERSION,'5.3.0','<')){
        return addslashes(json_encode($data));
    }else{
        return addslashes(json_encode($data,JSON_FORCE_OBJECT));
    }
}
/**
 * 字符截取 支持UTF8/GBK
 * @param $string
 * @param $length
 * @param $dot
 */
function str_cut($string, $length, $dot = '...') {
    //$string = iconv("UTF-8","GB2312",$string);
    $strlen = strlen($string);
    if ($strlen <= $length) {return $string;
    }

    $string = str_replace(array(' ', '&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵', ' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    //  if (strtolower(CHARSET) == 'utf-8') {
    $length = intval($length-strlen($dot)-$length/3);
    $n      = $tn      = $noc      = 0;
    while ($n < strlen($string)) {
        $t = ord($string[$n]);
        if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
            $tn = 1;
            $n++;
            $noc++;
        } elseif (194 <= $t && $t <= 223) {
            $tn = 2;
            $n += 2;
            $noc += 2;
        } elseif (224 <= $t && $t <= 239) {
            $tn = 3;
            $n += 3;
            $noc += 2;
        } elseif (240 <= $t && $t <= 247) {
            $tn = 4;
            $n += 4;
            $noc += 2;
        } elseif (248 <= $t && $t <= 251) {
            $tn = 5;
            $n += 5;
            $noc += 2;
        } elseif ($t == 252 || $t == 253) {
            $tn = 6;
            $n += 6;
            $noc += 2;
        } else {
            $n++;
        }
        if ($noc >= $length) {
            break;
        }
    }
    if ($noc > $length) {
        $n -= $tn;
    }
    $strcut = substr($string, 0, $n);
    $strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
    //  } else {
    //      $dotlen      = strlen($dot);
    //      $maxi        = $length-$dotlen-1;
    //      $current_str = '';
    //      $search_arr  = array('&', ' ', '"', "'", '“', '”', '—', '<', '>', '·', '…', '∵');
    //      $replace_arr = array('&amp;', '&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;', ' ');

    //      $search_flip = array_flip($search_arr);
    //      for ($i = 0; $i < $maxi; $i++) {
    //          $current_str = ord($string[$i]) > 127?$string[$i].$string[++$i]:$string[$i];
    //          if (in_array($current_str, $search_arr)) {
    //              $key         = $search_flip[$current_str];
    //              $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
    //          }
    //          $strcut .= $current_str;
    //      }
    //  }

    //$strcut = iconv("GB2312","UTF-8",$strcut);
    return $strcut.$dot;
}
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
//    foreach($rule as $v)
//        $rules[$v['id']] = $v;
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

/**
 * Introduction: 给图片连接添加参数,方便程序裁剪图片
 * User: arpeng
 * Date: 15/12/13
 * Time: 20:31
 * Last date: 15/12/13 20:31
 * Last user: arpeng
 * @param  string  $url 需要添加参数的URL
 * @param  int || string  $width  宽度
 * @param  int || string  $height  高度
 */
function cutUrl($url,$width,$height){
    if(!$url)return '';
    $info = pathinfo($url);
    return $info['dirname'].'/'.$info['filename'].'_'.$width.'_'.$height.'.'.$info['extension'];
}
/**
 * Introduction: 冒泡排序   用于blog  basic排序使用
 * User: arpeng
 * Date: 15/12/14
 * Time: 18:28
 * Last date: 15/12/14 18:28
 * Last user: arpeng
 */
function bubbleSort($array) {
    $count = count($array);
    for($i = 0; $i < $count; $i++){
        for($j = 0; $j < $count - $i -1; $j++){
            if($array[$j]['sort'] > $array[$j+1]['sort']){
                $temp = $array[$j];
                $array[$j] = $array[$j+1];
                $array[$j+1] = $temp;
            }
        }
    }
    return $array;
}
/**
 * Introduction: 获取注册时和登录时的用户名类型
 * User: arpeng
 * Date: 16/01/30
 * Time: 12:28
 * Last date: 16/01/30 12:28
 * Last user: arpeng
 * @param $sign String 登录时或者注册是输入的用户名
 */
function getSignType($sign){
    if(!$sign)return false;
    $return = [];
    $regxSign = [
        'mobile'   => "/^1[3|4|5|6|7|8]\d{9}$/",
        'email'    => "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/",
        'username' => "/^[a-zA-Z0-9-_]+$/",
    ];
    foreach($regxSign as $k => $v){
        if(preg_match($v,$sign)){
            $return[$k] = strtolower($sign);
            $return['type'] = $k;
            break;
        }
    }
    return $return;
}