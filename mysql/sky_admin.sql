/*
 Navicat MySQL Data Transfer

 Source Server         : skyclassroom_admin
 Source Server Version : 50542
 Source Host           : localhost
 Source Database       : sky_admin

 Target Server Version : 50542
 File Encoding         : utf-8

 Date: 11/20/2015 12:18:46 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `administrator`
-- ----------------------------
DROP TABLE IF EXISTS `administrator`;
CREATE TABLE `administrator` (
  `userid` smallint(6) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `auid` char(32) NOT NULL COMMENT '加密ID',
  `username` char(20) NOT NULL COMMENT '管理员用户名',
  `password` char(32) NOT NULL COMMENT '管理员密码',
  `encrypt` char(6) DEFAULT NULL COMMENT '密码加密因子',
  `truename` varchar(100) DEFAULT NULL COMMENT '''真实姓名''',
  `email` varchar(150) DEFAULT NULL COMMENT '管理员邮箱',
  `mobile` varchar(11) DEFAULT NULL COMMENT '''手机号码''',
  `remark` varchar(255) DEFAULT NULL COMMENT '''备注''',
  `nowip` varchar(20) DEFAULT '0.0.0.0' COMMENT '当前登录IP',
  `lastip` varchar(20) DEFAULT '0.0.0.0' COMMENT '上次登录ip',
  `nowtime` int(10) DEFAULT '0' COMMENT '当前登录数据',
  `loginnums` int(8) DEFAULT '0' COMMENT '管理员登录次数',
  `lasttime` int(10) NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `regtime` int(11) NOT NULL DEFAULT '0',
  `nicname` char(60) NOT NULL DEFAULT '' COMMENT '昵称或英文名',
  `regip` varchar(20) NOT NULL DEFAULT '0.0.0.0' COMMENT '注册IP',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '管理员状态 0禁用,1正常',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Records of `administrator`
-- ----------------------------
BEGIN;
INSERT INTO `administrator` VALUES ('1', 'bcac0e45fa1817a5f426f7ea9776f08e', 'admin', '8543f171acc4b778ee23fc3420a4b6cb', 'fsdj5A', '', '', '', '', '192.168.140.1', '192.168.140.1', '1447742657', '8', '1447740402', '0', 'Administriator', '', '1'), ('5', '865fe16186fd194453119cea33bc5f89', 'peter', '513cdaacc4315542d0caba5e4bd1c227', 'TJZuGY', '杨陈鹏', 'yangchenpeng@qq.com', '13123144888', '测试', '192.168.140.1', '192.168.140.1', '1447742619', '13', '1447620967', '1447252850', 'peter yang', '192.168.140.1', '1');
COMMIT;

-- ----------------------------
--  Table structure for `auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` char(100) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态:为1正常，为0禁用',
  `rules` char(80) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id， 多个规则","隔开',
  `description` char(255) NOT NULL COMMENT '描述信息',
  `enname` char(100) NOT NULL COMMENT '用户组英文名字',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `auth_group`
-- ----------------------------
BEGIN;
INSERT INTO `auth_group` VALUES ('3', '超级管理员', '1', '1,2,3,26,27,28,36,4,5,6,29,30,31,7,8,9,32,33,35,37,10,25', '', 'root'), ('4', '编辑', '1', '25', '', 'editor');
COMMIT;

-- ----------------------------
--  Table structure for `auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `auth_group_access`;
CREATE TABLE `auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL COMMENT '用户ID',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组ID',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `auth_group_access`
-- ----------------------------
BEGIN;
INSERT INTO `auth_group_access` VALUES ('5', '4'), ('10', '4');
COMMIT;

-- ----------------------------
--  Table structure for `auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `ppath` char(255) NOT NULL,
  `cpath` char(255) NOT NULL,
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '如果type为1， condition字段就可以定义规则表达式。 如定义{score}>5 and {score}<100 表示用户的分数在5-100之间时这条规则才会通过。（默认为1)',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态:为1正常，为0禁用',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  `ismenu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单 1为菜单，0为url',
  `child` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否拥有子集 1为有   0为没有',
  `sort` mediumint(5) NOT NULL DEFAULT '0' COMMENT '排序字段',
  `icon` char(60) NOT NULL DEFAULT '' COMMENT '字体图标类',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `auth_rule`
-- ----------------------------
BEGIN;
INSERT INTO `auth_rule` VALUES ('1', '0', '1', '1', 'Admin/Basic/index', '基础', '1', '1', '', '1', '1', '1', ''), ('2', '1', '1,2', '2,3,4,24', 'Admin/Administrator/index', '管理员', '1', '1', '', '1', '1', '0', 'icon-user-md'), ('3', '2', '1,2,3', '3,26,27,28,36', 'Admin/Administrator/lists', '管理员列表', '1', '1', '', '1', '1', '0', ''), ('4', '2', '1,2,4', '3', 'Admin/Administrator/create', '创建管理员', '1', '1', '', '1', '0', '1', ''), ('5', '1', '1,5', '5,6,7', 'Admin/Group/index', '管理组', '1', '1', '', '1', '1', '1', 'icon-group'), ('6', '5', '1,5,6', '6,29,30,31', 'Admin/Group/lists', '角色列表', '1', '1', '', '1', '1', '0', ''), ('7', '5', '1,5,7', '7', 'Admin/Group/create', '创建角色', '1', '1', '', '1', '0', '1', ''), ('8', '1', '1,8', '8,9,10', 'Admin/Rule/index', '规则', '1', '1', '', '1', '1', '2', 'icon-warning-sign'), ('9', '8', '1,8,9', '9,32,33,35,37', 'Admin/Rule/lists', '规则列表', '1', '1', '', '1', '1', '0', ''), ('10', '8', '1,8,10', '10', 'Admin/Rule/create', '新增规则', '1', '1', '', '1', '0', '1', ''), ('25', '0', '25', '25', 'Admin/Index/index', '首页', '1', '1', '', '1', '0', '0', ''), ('26', '3', '1,2,3,26', '26', 'Admin/Administrator/update', '修改', '1', '1', '', '0', '0', '0', ''), ('27', '3', '1,2,3,27', '27', 'Admin/Ajax/administratorDelete', '删除', '1', '1', '', '0', '0', '1', ''), ('28', '3', '1,2,3,28', '28', 'Admin/Ajax/administratorStatus', '禁用', '1', '1', '', '0', '0', '2', ''), ('29', '6', '1,5,6,29', '29', 'Admin/Group/update', '修改', '1', '1', '', '0', '0', '0', ''), ('30', '6', '1,5,6,30', '30', 'Admin/Ajax/groupStatus', '禁用', '1', '1', '', '0', '0', '1', ''), ('31', '6', '1,5,6,31', '31', 'Admin/Ajax/groupDelete', '删除', '1', '1', '', '0', '0', '2', ''), ('32', '9', '1,8,9,32', '32', 'Admin/Rule/update', '修改', '1', '1', '', '0', '0', '0', ''), ('33', '9', '1,8,9,33', '33', 'Admin/Ajax/ruleDelete', '删除', '1', '1', '', '0', '0', '0', ''), ('35', '9', '1,8,9,35', '35', 'Admin/Ajax/ruleStatus', '禁用', '1', '1', '', '1', '0', '0', ''), ('36', '3', '1,2,3,36', '36', 'Admin/Administrator/authorization', '授权', '1', '1', '', '0', '0', '3', ''), ('37', '9', '1,8,9,37', '37', 'Admin/Rule/sort', '排序', '1', '1', '', '0', '0', '0', '');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
