# Thinkphp-Admin-Auth
----------------------1.1.0------------------
1.去除redis支持，因为陪着起来比较麻烦，直接开启session就可以使用
2.去除子域名部署模式
  1.直接将域名解析到项目目录就可以使用
3.ningx 配置,如果是apache，请自行陪着
server{
      listen 80;
      #listen [::]:80;
      server_name admin.com www.admin.com;
      index index.html index.htm index.php default.html default.htm default.php;
      root  /Test/Admin/Program;

      include none.conf;
      error_page   404 = /404.html;
      location ~ [^/]\.php(/|$)
              {
                      #comment try_files $uri =404; to enable pathinfo
                      try_files $uri =404;
                      fastcgi_pass  unix:/tmp/php-cgi.sock;
                      fastcgi_index index.php;
                      include fastcgi.conf;
                      #fastcgi_param PHP_VALUE "session.name = codeclassroom_session";
                      #fastcgi_param PHP_VALUE "session.cookie_domain =.codeclassroom.com";
                      #include pathinfo.conf;
              }
      location / {
              if (!-e $request_filename) {
            	        rewrite ^(.*) /index.php?s=$1 last;
            	        break;
        	   	}
             autoindex on;
      }

}
4. Mysql
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
  `nowtime` int(11) DEFAULT '0' COMMENT '当前登录数据',
  `loginnums` int(8) DEFAULT '0' COMMENT '管理员登录次数',
  `lasttime` int(11) NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `regtime` int(11) NOT NULL DEFAULT '0',
  `nicname` char(60) NOT NULL DEFAULT '' COMMENT '昵称或英文名',
  `regip` varchar(20) NOT NULL DEFAULT '0.0.0.0' COMMENT '注册IP',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '管理员状态 0禁用,1正常',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Records of `administrator`
-- ----------------------------
BEGIN;
INSERT INTO `administrator` VALUES ('1', 'bcac0e45fa1817a5f426f7ea9776f08e', 'admin', '7c46db9bb961fc367fa8ecdd65d7c99c', 'whraaE', '', '', '', '', '192.168.171.1', '192.168.171.1', '1459763799', '152', '1459761332', '0', 'Administriator', '', '1', '0'), ('9', '71348daf8d1f7f326e986bc4fa23a1b7', 'peter', 'c20a802734d46a7b1275ca9eb1a73cec', 'MhdxRl', '杨陈鹏', 'yangchenpeng@qq.com', '13123144888', '', '192.168.171.1', '192.168.171.1', '1459763779', '11', '1459763068', '1459755583', 'peter', '192.168.171.1', '1', '1459755583');
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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
--  Records of `auth_group`
-- ----------------------------
BEGIN;
INSERT INTO `auth_group` VALUES ('3', '超级管理员', '1', '152,153,2,3,5,146,145,144,4,147,149,148,141,142,143,119,120,121', '', 'root'), ('10', '编辑', '1', '152,153,2,3,5,146,145,144,4,147,149,148,141,142,143', '', '');
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
--  Records of `auth_group_access`
-- ----------------------------
BEGIN;
INSERT INTO `auth_group_access` VALUES ('9', '10');
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
) ENGINE=MyISAM AUTO_INCREMENT=154 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
--  Records of `auth_rule`
-- ----------------------------
BEGIN;
INSERT INTO `auth_rule` VALUES ('2', '0', '2', '2,3,141,147', 'Admin/basic/', '基础', '1', '1', '', '1', '1', '1', ''), ('3', '2', '2,3', '3,4,5,106,107,108,109,112', 'Admin/basic/rule/*', '规则', '1', '1', '', '1', '1', '0', ''), ('4', '3', '2,3,4', '4', 'Admin/Basic/Rule/create', '新增', '1', '1', '', '1', '0', '1', ''), ('5', '3', '2,3,5', '5,110,111,144,145,146', 'Admin/Basic/Rule/lists', '列表', '1', '1', '', '1', '1', '0', ''), ('120', '119', '119,120', '120,121', 'Admin/Statistics/Server/*', '服务器', '1', '1', '', '1', '1', '0', 'fa fa-tasks'), ('121', '120', '119,120,121', '121', 'Admin/Statistics/Server/index', '服务器信息', '1', '1', '', '1', '0', '0', ''), ('119', '0', '119', '119,120', 'Admin/Statistics/*', '统计', '1', '1', '', '1', '1', '2', ''), ('142', '141', '2,141,142', '142', 'Admin/Basic/Group/lists', '列表', '1', '1', '', '1', '0', '0', ''), ('144', '5', '2,3,5,144', '144', 'Admin/Basic/Rule/update', '修改', '1', '1', '', '0', '0', '0', ''), ('145', '5', '2,3,5,145', '145', 'Admin/Basic/Rule/delete', '删除', '1', '1', '', '0', '0', '0', ''), ('152', '0', '152', '152,153', 'Admin/Index/Index/index', '首页', '1', '1', '', '1', '1', '0', ''), ('141', '2', '2,141', '141,142,143', 'Admin/Basic/Group/*', '管理组', '1', '1', '', '1', '1', '2', ''), ('143', '141', '2,141,143', '143', 'Admin/Basic/Group/create', '新增', '1', '1', '', '1', '0', '1', ''), ('146', '5', '2,3,5,146', '146', 'Admin/Basic/Rule/status', '修改状态', '1', '1', '', '0', '0', '0', ''), ('147', '2', '2,147', '147,148,149', 'Admin/Basic/Administrator/*', '管理员', '1', '1', '', '1', '1', '1', ''), ('148', '147', '2,147,148', '148', 'Admin/Basic/Administrator/lists', '列表', '1', '1', '', '1', '0', '0', ''), ('149', '147', '2,147,149', '149', 'Admin/Basic/Administrator/create', '添加', '1', '1', '', '1', '0', '0', ''), ('153', '152', '152,153', '153', 'Admin/Index/Index/_index', '基本信息', '1', '1', '', '1', '0', '0', '');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;




----------------------1.0.0------------------
基于Thinkphp的站点通用管理员后台,基于Auth验证
1.需要redis支持
  1.1 session需要redis支持
2.需要解析两个域名到项目上面
  2.1 解析到项目目录website
  2.2 解析到资源目录statics
  2.3 需要在配置文件里面配置资源目录路径
3. 数据库表在mysql文件夹下面
4. nginx配置在nginx目录下面
