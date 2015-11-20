<?php
namespace Org\Util;
/*********************************************************************************
 * InitPHP 3.8.1 国产PHP开发框架   生成树状结构
 *-------------------------------------------------------------------------------
 * 版权所有: CopyRight By initphp.com
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 *-------------------------------------------------------------------------------
 * Author:liubo Dtime:2014-11-25
 * explain :
 * $tree = InitPHP::getLibrary('ntree');
 * $tree ->intt($array);
 * $info = $tree->  [public function ];
 *
 *
 ***********************************************************************************/
class Tree {
    public $arr           = array();
    public $icon          = array('┇&nbsp;', '├&nbsp;', '└&nbsp;');
    private $result_array = '';
    //计算树状结构的最深层，默认不允许超过5层结构，有效防止数据结构混乱造成无限循环
    private $deep = 1;
    /**
     * 注意，array的索引要和索引值id值相同
     * 例子：
     * array(
     *      1 => array('id'=>'1','pid'=>0,'name'=>'一级栏目A'),
     *      2 => array('id'=>'2','pid'=>0,'name'=>'一级栏目B'),
     *      3 => array('id'=>'3','pid'=>1,'name'=>'二级栏目A'),
     *      4 => array('id'=>'4','pid'=>1,'name'=>'二级栏目B'),
     *      5 => array('id'=>'5','pid'=>2,'name'=>'二级栏目C'),
     *      6 => array('id'=>'6','pid'=>3,'name'=>'三级栏目A'),
     *      7 => array('id'=>'7','pid'=>3,'name'=>'三级栏目B')
     *      )
     */
    function init($arr) {
        $this->arr          = $arr;
        $this->result_array = '';
    }

    /**
     * 得到父级数组
     * @param int
     * @return array
     */
    public function get_parent($k_id) {
        $arrays = array();
        if (!isset($this->arr[$k_id])) {return FALSE;
        }

        $pid = $this->arr[$k_id]['pid'];
        $pid = $this->arr[$pid]['pid'];
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if ($a['pid'] == $pid) {$arrays[$id] = $a;
                }
            }
        }

        return $arrays;
    }

    /**
     * 得到子级数组
     * @param int
     * @return array
     */
    public function get_child($k_id) {
        $arrays = array();
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if ($a['pid'] == $k_id) {
                    $arrays[$id] = $a;
                }
            }
        }

        $this->deep++;
        return $arrays?$arrays:FALSE;
    }

    /**
     * 得到树型结构
     * @param int ID，表示获得这个ID下的所有子级
     * @param string 生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
     * @param int 被选中的ID，比如在做树型下拉框的时候需要用到
     * @return string
     */
    public function create($k_id, $str, $sid = 0, $adds = '', $isDisabledParent = true) {
        if ($this->deep > 5) {
            echo L('array structure error');
            exit;
        }
        $number = 1;
        $child  = $this->get_child($k_id);

        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $a) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds?$this->icon[0]:'';
                }
                $spacer = $adds?$adds.$j:'';
                if ($isDisabledParent && $a['child']) {

                    $disabled = "disabled='disabled'";
                }
                if ($isDisabledParent && !$a['pid']) {

                    $disabled = "disabled='disabled'";
                }
                $selected = ($id == $sid?"selected":'');
                @extract($a);
                unset($a['child']);
                eval("\$nstr = \"$str\";");
                $this->result_array .= $nstr;
                $this->create($id, $str, $sid, $adds.$k.'&nbsp;&nbsp;',$isDisabledParent);
                $number++;
            }
        }
        $this->deep = 1;
        return $this->result_array;
    }

    /**
     * [get_treeview description]
     * @param  [type]  $cid          [description]
     * @param  string  $treeid       [description]
     * @param  string  $str          [description]
     * @param  string  $str2         [description]
     * @param  integer $showlevel    [description]
     * @param  integer $currentlevel [description]
     * @param  boolean $have_child   [是否包含子集]
     * @return [type]                [description]
     */
    public function get_treeview($cid, $treeid = 'tree', $str = "<li><a href='javascript:w(\$cid);' class='i-t'>\$name</a></li>", $str2 = "<li><a href='javascript:w(\$cid);' onclick='o_p(\$cid,this)' class='i-t'>\$name</a>", $showlevel = 0, $currentlevel = 1, $have_child = true) {

        $child = $this->get_child($cid);
        if (!defined('EFFECTED_INIT')) {
            $effected = ' id="'.$treeid.'"';
            define('EFFECTED_INIT', 1);
        } else {
            $effected = '';
        }
        if (!$have_child) {$this->str .= '<ul'.$effected.'>';
        }

        if (!empty($child)) {
            foreach ($child as $id => $a) {
                @extract($a);
                $this->str .= $have_child?'<ul><li>':'';
                $have_child = true;
                if ($this->get_child($id)) {
                    eval("\$nstr = \"$str2\";");
                    $this->str .= $nstr;
                    if ($showlevel == 0 || ($showlevel > 0 && $showlevel > $currentlevel)) {
                        $this->get_treeview($id, '', $str, $str2, $showlevel, $currentlevel+1, TRUE);
                    }
                } else {
                    eval("\$nstr = \"$str\";");
                    $this->str .= $nstr;
                }
                $this->str .= $have_child?'</li></ul>':'</li>';
            }
        }
        if (!$have_child) {$this->str .= '</ul>';
        }

        return $this->str;
    }
}
?>