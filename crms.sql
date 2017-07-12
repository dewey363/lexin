/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50635
 Source Host           : localhost
 Source Database       : crms

 Target Server Type    : MySQL
 Target Server Version : 50635
 File Encoding         : utf-8

 Date: 07/12/2017 23:10:01 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `yl_app_user`
-- ----------------------------
DROP TABLE IF EXISTS `yl_app_user`;
CREATE TABLE `yl_app_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码；sp_password加密',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `phone` varchar(20) NOT NULL COMMENT '手机号',
  `avatar` varchar(255) DEFAULT NULL COMMENT '用户头像，相对于upload/avatar目录',
  `sex` tinyint(1) DEFAULT '0' COMMENT '性别；0：保密，1：男；2：女',
  `birthday` int(11) DEFAULT '0' COMMENT '生日',
  `signature` varchar(255) DEFAULT NULL COMMENT '个性签名',
  `last_login_ip` varchar(16) DEFAULT NULL COMMENT '最后登录ip',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `user_status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证',
  `qrcode` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COMMENT='app用户表';

-- ----------------------------
--  Records of `yl_app_user`
-- ----------------------------
BEGIN;
INSERT INTO `yl_app_user` VALUES ('37', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', 'go_die', '17512536980', '/image/201707101747558443.jpg', '0', '0', '', '', '0', '1498205056', '1', '/qrcode/17512536980.png'), ('36', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '18602552458', '/image/201706231840046812.jpg', '0', '0', '', '', '0', '1498204337', '1', '/qrcode/18602552458.png'), ('6', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '测试的', '15617567297', '/image/201706281923077473.png', '0', '0', '', '', '0', '1497940517', '1', '/qrcode/15617567297.png'), ('27', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '17809090909', null, '0', '0', null, null, '0', '1498128848', '2', null), ('28', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '17809090901', null, '0', '0', null, null, '0', '1498128848', '2', null), ('29', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '17809090911', null, '0', '0', null, null, '0', '1498128848', '2', null), ('30', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '17809090912', null, '0', '0', null, null, '0', '1498128848', '2', null), ('31', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '17809090913', null, '0', '0', null, null, '0', '1498129121', '2', null), ('39', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '17809090944', null, '0', '0', null, null, '0', '1498206293', '2', null), ('40', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '17809090955', null, '0', '0', null, null, '0', '1498206415', '2', null), ('41', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '18602542459', null, '0', '0', null, null, '0', '1498206481', '2', null), ('42', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '18602542460', null, '0', '0', null, null, '0', '1498206842', '2', null), ('43', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '18602542457', null, '0', '0', null, null, '0', '1498206875', '2', null), ('44', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '18602542557', null, '0', '0', null, null, '0', '1498206965', '2', null), ('45', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '18612542557', null, '0', '0', null, null, '0', '1498207257', '2', null), ('46', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '17509090909', null, '0', '0', null, null, '0', '1498207297', '2', null), ('47', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '18612542579', null, '0', '0', null, null, '0', '1498207734', '2', null), ('48', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '18612542599', null, '0', '0', null, null, '0', '1498207805', '2', null), ('49', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '18994725813', '', '0', '0', '', '', '0', '1498534670', '1', '/qrcode/18994725813.png'), ('50', '', '', '', null, '0', '0', null, null, '0', '0', '2', null), ('51', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '13809093333', null, '0', '0', null, null, '0', '1499331509', '2', null), ('52', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '13809093334', null, '0', '0', null, null, '0', '1499331552', '2', null), ('54', '7c4a8d09ca3762af61e59520943dc26494f8941b', '', '18952025306', '', '0', '0', '', '', '0', '1499684513', '1', '/qrcode/18952025306.png'), ('55', '3d4f2bf07dc1be38b20cd6e46949a1071f9d0e3d', '', '32312312', null, '0', '0', null, null, '0', '1499849198', '2', null);
COMMIT;

-- ----------------------------
--  Table structure for `yl_asset`
-- ----------------------------
DROP TABLE IF EXISTS `yl_asset`;
CREATE TABLE `yl_asset` (
  `aid` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户 id',
  `key` varchar(50) NOT NULL COMMENT '资源 key',
  `filename` varchar(50) DEFAULT NULL COMMENT '文件名',
  `filesize` int(11) DEFAULT NULL COMMENT '文件大小,单位Byte',
  `filepath` varchar(200) NOT NULL COMMENT '文件路径，相对于 upload 目录，可以为 url',
  `uploadtime` int(11) NOT NULL COMMENT '上传时间',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1：可用，0：删除，不可用',
  `meta` text COMMENT '其它详细信息，JSON格式',
  `suffix` varchar(50) DEFAULT NULL COMMENT '文件后缀名，不包括点',
  `download_times` int(11) NOT NULL DEFAULT '0' COMMENT '下载次数',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源表';

-- ----------------------------
--  Table structure for `yl_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `yl_attachment`;
CREATE TABLE `yl_attachment` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '附件地址',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '1视频',
  `created_time` int(11) unsigned DEFAULT NULL COMMENT '上传时间',
  `uid` int(10) DEFAULT NULL COMMENT '户用id',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `file_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
--  Table structure for `yl_auth_access`
-- ----------------------------
DROP TABLE IF EXISTS `yl_auth_access`;
CREATE TABLE `yl_auth_access` (
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色',
  `rule_name` varchar(255) NOT NULL COMMENT '规则唯一英文标识,全小写',
  `type` varchar(30) DEFAULT NULL COMMENT '权限规则分类，请加应用前缀,如admin_',
  KEY `role_id` (`role_id`),
  KEY `rule_name` (`rule_name`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限授权表';

-- ----------------------------
--  Records of `yl_auth_access`
-- ----------------------------
BEGIN;
INSERT INTO `yl_auth_access` VALUES ('2', 'admin/student/index', 'admin_url'), ('2', 'admin/student/default', 'admin_url');
COMMIT;

-- ----------------------------
--  Table structure for `yl_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `yl_auth_rule`;
CREATE TABLE `yl_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` varchar(30) NOT NULL DEFAULT '1' COMMENT '权限规则分类，请加应用前缀,如admin_',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `param` varchar(255) DEFAULT NULL COMMENT '额外url参数',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=192 DEFAULT CHARSET=utf8 COMMENT='权限规则表';

-- ----------------------------
--  Records of `yl_auth_rule`
-- ----------------------------
BEGIN;
INSERT INTO `yl_auth_rule` VALUES ('1', 'Admin', 'admin_url', 'admin/content/default', null, '内容管理', '1', ''), ('2', 'Api', 'admin_url', 'api/guestbookadmin/index', null, '所有留言', '1', ''), ('3', 'Api', 'admin_url', 'api/guestbookadmin/delete', null, '删除网站留言', '1', ''), ('4', 'Comment', 'admin_url', 'comment/commentadmin/index', null, '评论管理', '1', ''), ('5', 'Comment', 'admin_url', 'comment/commentadmin/delete', null, '删除评论', '1', ''), ('6', 'Comment', 'admin_url', 'comment/commentadmin/check', null, '评论审核', '1', ''), ('7', 'Portal', 'admin_url', 'portal/adminpost/index', null, '文章管理', '1', ''), ('8', 'Portal', 'admin_url', 'portal/adminpost/listorders', null, '文章排序', '1', ''), ('9', 'Portal', 'admin_url', 'portal/adminpost/top', null, '文章置顶', '1', ''), ('10', 'Portal', 'admin_url', 'portal/adminpost/recommend', null, '文章推荐', '1', ''), ('11', 'Portal', 'admin_url', 'portal/adminpost/move', null, '批量移动', '1', ''), ('12', 'Portal', 'admin_url', 'portal/adminpost/check', null, '文章审核', '1', ''), ('13', 'Portal', 'admin_url', 'portal/adminpost/delete', null, '删除文章', '1', ''), ('14', 'Portal', 'admin_url', 'portal/adminpost/edit', null, '编辑文章', '1', ''), ('15', 'Portal', 'admin_url', 'portal/adminpost/edit_post', null, '提交编辑', '1', ''), ('16', 'Portal', 'admin_url', 'portal/adminpost/add', null, '添加文章', '1', ''), ('17', 'Portal', 'admin_url', 'portal/adminpost/add_post', null, '提交添加', '1', ''), ('18', 'Portal', 'admin_url', 'portal/adminterm/index', null, '分类管理', '1', ''), ('19', 'Portal', 'admin_url', 'portal/adminterm/listorders', null, '文章分类排序', '1', ''), ('20', 'Portal', 'admin_url', 'portal/adminterm/delete', null, '删除分类', '1', ''), ('21', 'Portal', 'admin_url', 'portal/adminterm/edit', null, '编辑分类', '1', ''), ('22', 'Portal', 'admin_url', 'portal/adminterm/edit_post', null, '提交编辑', '1', ''), ('23', 'Portal', 'admin_url', 'portal/adminterm/add', null, '添加分类', '1', ''), ('24', 'Portal', 'admin_url', 'portal/adminterm/add_post', null, '提交添加', '1', ''), ('25', 'Portal', 'admin_url', 'portal/adminpage/index', null, '公告管理', '1', ''), ('26', 'Portal', 'admin_url', 'portal/adminpage/listorders', null, '页面排序', '1', ''), ('27', 'Portal', 'admin_url', 'portal/adminpage/delete', null, '删除页面', '1', ''), ('28', 'Portal', 'admin_url', 'portal/adminpage/edit', null, '编辑页面', '1', ''), ('29', 'Portal', 'admin_url', 'portal/adminpage/edit_post', null, '提交编辑', '1', ''), ('30', 'Portal', 'admin_url', 'portal/adminpage/add', null, '添加公告', '1', ''), ('31', 'Portal', 'admin_url', 'portal/adminpage/add_post', null, '提交添加', '1', ''), ('32', 'Admin', 'admin_url', 'admin/recycle/default', null, '回收站', '1', ''), ('33', 'Portal', 'admin_url', 'portal/adminpost/recyclebin', null, '文章回收', '1', ''), ('34', 'Portal', 'admin_url', 'portal/adminpost/restore', null, '文章还原', '1', ''), ('35', 'Portal', 'admin_url', 'portal/adminpost/clean', null, '彻底删除', '1', ''), ('36', 'Portal', 'admin_url', 'portal/adminpage/recyclebin', null, '页面回收', '1', ''), ('37', 'Portal', 'admin_url', 'portal/adminpage/clean', null, '彻底删除', '1', ''), ('38', 'Portal', 'admin_url', 'portal/adminpage/restore', null, '页面还原', '1', ''), ('39', 'Admin', 'admin_url', 'admin/extension/default', null, '扩展工具', '1', ''), ('40', 'Admin', 'admin_url', 'admin/backup/default', null, '备份管理', '1', ''), ('41', 'Admin', 'admin_url', 'admin/backup/restore', null, '数据还原', '1', ''), ('42', 'Admin', 'admin_url', 'admin/backup/index', null, '数据备份', '1', ''), ('43', 'Admin', 'admin_url', 'admin/backup/index_post', null, '提交数据备份', '1', ''), ('44', 'Admin', 'admin_url', 'admin/backup/download', null, '下载备份', '1', ''), ('45', 'Admin', 'admin_url', 'admin/backup/del_backup', null, '删除备份', '1', ''), ('46', 'Admin', 'admin_url', 'admin/backup/import', null, '数据备份导入', '1', ''), ('47', 'Admin', 'admin_url', 'admin/plugin/index', null, '插件管理', '1', ''), ('48', 'Admin', 'admin_url', 'admin/plugin/toggle', null, '插件启用切换', '1', ''), ('49', 'Admin', 'admin_url', 'admin/plugin/setting', null, '插件设置', '1', ''), ('50', 'Admin', 'admin_url', 'admin/plugin/setting_post', null, '插件设置提交', '1', ''), ('51', 'Admin', 'admin_url', 'admin/plugin/install', null, '插件安装', '1', ''), ('52', 'Admin', 'admin_url', 'admin/plugin/uninstall', null, '插件卸载', '1', ''), ('53', 'Admin', 'admin_url', 'admin/slide/default', null, '幻灯片', '1', ''), ('54', 'Admin', 'admin_url', 'admin/slide/index', null, '幻灯片管理', '1', ''), ('55', 'Admin', 'admin_url', 'admin/slide/listorders', null, '幻灯片排序', '1', ''), ('56', 'Admin', 'admin_url', 'admin/slide/toggle', null, '幻灯片显示切换', '1', ''), ('57', 'Admin', 'admin_url', 'admin/slide/delete', null, '删除幻灯片', '1', ''), ('58', 'Admin', 'admin_url', 'admin/slide/edit', null, '编辑幻灯片', '1', ''), ('59', 'Admin', 'admin_url', 'admin/slide/edit_post', null, '提交编辑', '1', ''), ('60', 'Admin', 'admin_url', 'admin/slide/add', null, '添加幻灯片', '1', ''), ('61', 'Admin', 'admin_url', 'admin/slide/add_post', null, '提交添加', '1', ''), ('62', 'Admin', 'admin_url', 'admin/slidecat/index', null, '幻灯片分类', '1', ''), ('63', 'Admin', 'admin_url', 'admin/slidecat/delete', null, '删除分类', '1', ''), ('64', 'Admin', 'admin_url', 'admin/slidecat/edit', null, '编辑分类', '1', ''), ('65', 'Admin', 'admin_url', 'admin/slidecat/edit_post', null, '提交编辑', '1', ''), ('66', 'Admin', 'admin_url', 'admin/slidecat/add', null, '添加分类', '1', ''), ('67', 'Admin', 'admin_url', 'admin/slidecat/add_post', null, '提交添加', '1', ''), ('68', 'Admin', 'admin_url', 'admin/ad/index', null, '网站广告', '1', ''), ('69', 'Admin', 'admin_url', 'admin/ad/toggle', null, '广告显示切换', '1', ''), ('70', 'Admin', 'admin_url', 'admin/ad/delete', null, '删除广告', '1', ''), ('71', 'Admin', 'admin_url', 'admin/ad/edit', null, '编辑广告', '1', ''), ('72', 'Admin', 'admin_url', 'admin/ad/edit_post', null, '提交编辑', '1', ''), ('73', 'Admin', 'admin_url', 'admin/ad/add', null, '添加广告', '1', ''), ('74', 'Admin', 'admin_url', 'admin/ad/add_post', null, '提交添加', '1', ''), ('75', 'Admin', 'admin_url', 'admin/link/index', null, '友情链接', '1', ''), ('76', 'Admin', 'admin_url', 'admin/link/listorders', null, '友情链接排序', '1', ''), ('77', 'Admin', 'admin_url', 'admin/link/toggle', null, '友链显示切换', '1', ''), ('78', 'Admin', 'admin_url', 'admin/link/delete', null, '删除友情链接', '1', ''), ('79', 'Admin', 'admin_url', 'admin/link/edit', null, '编辑友情链接', '1', ''), ('80', 'Admin', 'admin_url', 'admin/link/edit_post', null, '提交编辑', '1', ''), ('81', 'Admin', 'admin_url', 'admin/link/add', null, '添加友情链接', '1', ''), ('82', 'Admin', 'admin_url', 'admin/link/add_post', null, '提交添加', '1', ''), ('83', 'Api', 'admin_url', 'api/oauthadmin/setting', null, '第三方登陆', '1', ''), ('84', 'Api', 'admin_url', 'api/oauthadmin/setting_post', null, '提交设置', '1', ''), ('85', 'Admin', 'admin_url', 'admin/menu/default', null, '菜单管理', '1', ''), ('86', 'Admin', 'admin_url', 'admin/navcat/default1', null, '前台菜单', '1', ''), ('87', 'Admin', 'admin_url', 'admin/nav/index', null, '菜单管理', '1', ''), ('88', 'Admin', 'admin_url', 'admin/nav/listorders', null, '前台导航排序', '1', ''), ('89', 'Admin', 'admin_url', 'admin/nav/delete', null, '删除菜单', '1', ''), ('90', 'Admin', 'admin_url', 'admin/nav/edit', null, '编辑菜单', '1', ''), ('91', 'Admin', 'admin_url', 'admin/nav/edit_post', null, '提交编辑', '1', ''), ('92', 'Admin', 'admin_url', 'admin/nav/add', null, '添加菜单', '1', ''), ('93', 'Admin', 'admin_url', 'admin/nav/add_post', null, '提交添加', '1', ''), ('94', 'Admin', 'admin_url', 'admin/navcat/index', null, '菜单分类', '1', ''), ('95', 'Admin', 'admin_url', 'admin/navcat/delete', null, '删除分类', '1', ''), ('96', 'Admin', 'admin_url', 'admin/navcat/edit', null, '编辑分类', '1', ''), ('97', 'Admin', 'admin_url', 'admin/navcat/edit_post', null, '提交编辑', '1', ''), ('98', 'Admin', 'admin_url', 'admin/navcat/add', null, '添加分类', '1', ''), ('99', 'Admin', 'admin_url', 'admin/navcat/add_post', null, '提交添加', '1', ''), ('100', 'Admin', 'admin_url', 'admin/menu/index', null, '后台菜单', '1', ''), ('101', 'Admin', 'admin_url', 'admin/menu/add', null, '添加菜单', '1', ''), ('102', 'Admin', 'admin_url', 'admin/menu/add_post', null, '提交添加', '1', ''), ('103', 'Admin', 'admin_url', 'admin/menu/listorders', null, '后台菜单排序', '1', ''), ('104', 'Admin', 'admin_url', 'admin/menu/export_menu', null, '菜单备份', '1', ''), ('105', 'Admin', 'admin_url', 'admin/menu/edit', null, '编辑菜单', '1', ''), ('106', 'Admin', 'admin_url', 'admin/menu/edit_post', null, '提交编辑', '1', ''), ('107', 'Admin', 'admin_url', 'admin/menu/delete', null, '删除菜单', '1', ''), ('108', 'Admin', 'admin_url', 'admin/menu/lists', null, '所有菜单', '1', ''), ('109', 'Admin', 'admin_url', 'admin/setting/default', null, '设置', '1', ''), ('110', 'Admin', 'admin_url', 'admin/setting/userdefault', null, '个人信息', '1', ''), ('111', 'Admin', 'admin_url', 'admin/user/userinfo', null, '修改信息', '1', ''), ('112', 'Admin', 'admin_url', 'admin/user/userinfo_post', null, '修改信息提交', '1', ''), ('113', 'Admin', 'admin_url', 'admin/setting/password', null, '修改密码', '1', ''), ('114', 'Admin', 'admin_url', 'admin/setting/password_post', null, '提交修改', '1', ''), ('115', 'Admin', 'admin_url', 'admin/setting/site', null, '网站信息', '1', ''), ('116', 'Admin', 'admin_url', 'admin/setting/site_post', null, '提交修改', '1', ''), ('117', 'Admin', 'admin_url', 'admin/route/index', null, '路由列表', '1', ''), ('118', 'Admin', 'admin_url', 'admin/route/add', null, '路由添加', '1', ''), ('119', 'Admin', 'admin_url', 'admin/route/add_post', null, '路由添加提交', '1', ''), ('120', 'Admin', 'admin_url', 'admin/route/edit', null, '路由编辑', '1', ''), ('121', 'Admin', 'admin_url', 'admin/route/edit_post', null, '路由编辑提交', '1', ''), ('122', 'Admin', 'admin_url', 'admin/route/delete', null, '路由删除', '1', ''), ('123', 'Admin', 'admin_url', 'admin/route/ban', null, '路由禁止', '1', ''), ('124', 'Admin', 'admin_url', 'admin/route/open', null, '路由启用', '1', ''), ('125', 'Admin', 'admin_url', 'admin/route/listorders', null, '路由排序', '1', ''), ('126', 'Admin', 'admin_url', 'admin/mailer/default', null, '邮箱配置', '1', ''), ('127', 'Admin', 'admin_url', 'admin/mailer/index', null, 'SMTP配置', '1', ''), ('128', 'Admin', 'admin_url', 'admin/mailer/index_post', null, '提交配置', '1', ''), ('129', 'Admin', 'admin_url', 'admin/mailer/active', null, '注册邮件模板', '1', ''), ('130', 'Admin', 'admin_url', 'admin/mailer/active_post', null, '提交模板', '1', ''), ('131', 'Admin', 'admin_url', 'admin/setting/clearcache', null, '清除缓存', '1', ''), ('132', 'Admin', 'admin_url', 'admin/admin/default', null, '管理组', '1', ''), ('133', 'User', 'admin_url', 'user/indexadmin/default1', null, '用户组', '1', ''), ('134', 'User', 'admin_url', 'user/indexadmin/index', null, '本站用户', '1', ''), ('135', 'User', 'admin_url', 'user/indexadmin/ban', null, '拉黑会员', '1', ''), ('136', 'User', 'admin_url', 'user/indexadmin/cancelban', null, '启用会员', '1', ''), ('137', 'User', 'admin_url', 'user/oauthadmin/index', null, '第三方用户', '1', ''), ('138', 'User', 'admin_url', 'user/oauthadmin/delete', null, '第三方用户解绑', '1', ''), ('139', 'User', 'admin_url', 'user/indexadmin/default3', null, '管理组', '1', ''), ('140', 'Admin', 'admin_url', 'admin/rbac/index', null, '角色管理', '1', ''), ('141', 'Admin', 'admin_url', 'admin/rbac/member', null, '成员管理', '1', ''), ('142', 'Admin', 'admin_url', 'admin/rbac/authorize', null, '权限设置', '1', ''), ('143', 'Admin', 'admin_url', 'admin/rbac/authorize_post', null, '提交设置', '1', ''), ('144', 'Admin', 'admin_url', 'admin/rbac/roleedit', null, '编辑角色', '1', ''), ('145', 'Admin', 'admin_url', 'admin/rbac/roleedit_post', null, '提交编辑', '1', ''), ('146', 'Admin', 'admin_url', 'admin/rbac/roledelete', null, '删除角色', '1', ''), ('147', 'Admin', 'admin_url', 'admin/rbac/roleadd', null, '添加角色', '1', ''), ('148', 'Admin', 'admin_url', 'admin/rbac/roleadd_post', null, '提交添加', '1', ''), ('149', 'Admin', 'admin_url', 'admin/user/index', null, '管理员', '1', ''), ('150', 'Admin', 'admin_url', 'admin/user/delete', null, '删除管理员', '1', ''), ('151', 'Admin', 'admin_url', 'admin/user/edit', null, '管理员编辑', '1', ''), ('152', 'Admin', 'admin_url', 'admin/user/edit_post', null, '编辑提交', '1', ''), ('153', 'Admin', 'admin_url', 'admin/user/add', null, '管理员添加', '1', ''), ('154', 'Admin', 'admin_url', 'admin/user/add_post', null, '添加提交', '1', ''), ('155', 'Admin', 'admin_url', 'admin/plugin/update', null, '插件更新', '1', ''), ('156', 'Admin', 'admin_url', 'admin/storage/index', null, '文件存储', '1', ''), ('157', 'Admin', 'admin_url', 'admin/storage/setting_post', null, '文件存储设置提交', '1', ''), ('158', 'Admin', 'admin_url', 'admin/slide/ban', null, '禁用幻灯片', '1', ''), ('159', 'Admin', 'admin_url', 'admin/slide/cancelban', null, '启用幻灯片', '1', ''), ('160', 'Admin', 'admin_url', 'admin/user/ban', null, '禁用管理员', '1', ''), ('161', 'Admin', 'admin_url', 'admin/user/cancelban', null, '启用管理员', '1', ''), ('162', 'Demo', 'admin_url', 'demo/adminindex/index', null, '', '1', ''), ('163', 'Demo', 'admin_url', 'demo/adminindex/last', null, '', '1', ''), ('166', 'Admin', 'admin_url', 'admin/mailer/test', null, '测试邮件', '1', ''), ('167', 'Admin', 'admin_url', 'admin/setting/upload', null, '上传设置', '1', ''), ('168', 'Admin', 'admin_url', 'admin/setting/upload_post', null, '上传设置提交', '1', ''), ('169', 'Portal', 'admin_url', 'portal/adminpost/copy', null, '文章批量复制', '1', ''), ('170', 'Admin', 'admin_url', 'admin/menu/backup_menu', null, '备份菜单', '1', ''), ('171', 'Admin', 'admin_url', 'admin/menu/export_menu_lang', null, '导出后台菜单多语言包', '1', ''), ('172', 'Admin', 'admin_url', 'admin/menu/restore_menu', null, '还原菜单', '1', ''), ('173', 'Admin', 'admin_url', 'admin/menu/getactions', null, '导入新菜单', '1', ''), ('174', 'Admin', 'admin_url', 'admin/site/index', null, '网站授权', '1', ''), ('175', 'Admin', 'admin_url', 'admin/site/toggle', null, '网站授权显示切换', '1', ''), ('176', 'Admin', 'admin_url', 'admin/site/delete', null, '删除授权', '1', ''), ('177', 'Admin', 'admin_url', 'admin/site/edit', null, '编辑授权', '1', ''), ('178', 'Admin', 'admin_url', 'admin/site/edit_post', null, '提交编辑', '1', ''), ('179', 'Admin', 'admin_url', 'admin/site/add', null, '添加授权', '1', ''), ('180', 'Admin', 'admin_url', 'admin/site/add_post', null, '提交添加', '1', ''), ('181', 'Admin', 'admin_url', 'admin/student/default', null, '学生管理', '1', ''), ('182', 'Admin', 'admin_url', 'admin/student/index', null, '学生列表', '1', ''), ('183', 'Admin', 'admin_url', 'admin/school/default', null, '学校管理管理', '1', ''), ('184', 'Admin', 'admin_url', 'admin/staff/default', null, '人事管理', '1', ''), ('185', 'Admin', 'admin_url', 'admin/class/default', null, '班级管理', '1', ''), ('186', 'Admin', 'admin_url', 'admin/school/index', null, '学校列表', '1', ''), ('187', 'Admin', 'admin_url', 'admin/staff/index', null, '人事列表', '1', ''), ('188', 'Admin', 'admin_url', 'admin/class/index', null, '班级列表', '1', ''), ('189', 'Portal', 'admin_url', 'portal/adminpage/view', null, '公告详情', '1', ''), ('190', 'Admin', 'admin_url', 'admin/contract/index', null, '合同管理', '1', ''), ('191', 'Admin', 'admin_url', 'admin/finance/index', null, '财务管理', '1', '');
COMMIT;

-- ----------------------------
--  Table structure for `yl_class`
-- ----------------------------
DROP TABLE IF EXISTS `yl_class`;
CREATE TABLE `yl_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '班级名称',
  `course` varchar(50) DEFAULT NULL COMMENT '课程名称',
  `teacher` int(11) unsigned DEFAULT '0' COMMENT '老师',
  `open_date` int(11) unsigned DEFAULT '0' COMMENT '开班日期',
  `class_time` varchar(30) DEFAULT NULL COMMENT '上课时间',
  `week_day` int(11) unsigned DEFAULT '0' COMMENT '周几1周一2周二3周三4周四5周五6周六7周日',
  `student_population` int(11) unsigned DEFAULT '0' COMMENT '学生人数',
  `status` tinyint(1) DEFAULT '0' COMMENT '1停课',
  `holiday` tinyint(1) unsigned DEFAULT '0' COMMENT '1放假',
  `times` int(11) unsigned DEFAULT '0' COMMENT '每周几次课',
  `number` varchar(100) DEFAULT '0' COMMENT '教室编号',
  `school` int(11) unsigned DEFAULT '0' COMMENT '学校',
  `stu_id` text COMMENT '学生id',
  `consume_times` int(10) unsigned DEFAULT '0' COMMENT '每次刷卡消耗课时',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='班级表';

-- ----------------------------
--  Records of `yl_class`
-- ----------------------------
BEGIN;
INSERT INTO `yl_class` VALUES ('1', 'PA-001', '钢琴课', '2', '1498752000', '1498752000', '1', '10', '0', '0', '0', '001', '1', '3', '0'), ('6', ' 测试11', '乐器', '2', '1499184000', '1500480000', '2', '22', '2', '0', '0', '32231', '1', '2', '0'), ('7', '查尔达什', '水淀粉', '2', '1499011200', '1499097600', '3', '23', '2', '0', '0', '3234', '1', '2,3', '0');
COMMIT;

-- ----------------------------
--  Table structure for `yl_class_consum`
-- ----------------------------
DROP TABLE IF EXISTS `yl_class_consum`;
CREATE TABLE `yl_class_consum` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `stu_id` int(11) DEFAULT '0' COMMENT '学生id',
  `parent_id` int(11) DEFAULT '0' COMMENT '家长id',
  `class_id` int(11) DEFAULT '0' COMMENT '班级id',
  `class_hour` varchar(20) DEFAULT NULL COMMENT '消耗课时',
  `add_time` int(11) DEFAULT '0' COMMENT '消耗时间',
  `card_info` varchar(100) DEFAULT NULL COMMENT '卡号',
  `is_view` tinyint(1) DEFAULT '1' COMMENT '默认1未查看2已查看',
  `type` tinyint(3) unsigned DEFAULT '0' COMMENT '0正常消耗1补课2缺课',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='课时消耗记录';

-- ----------------------------
--  Records of `yl_class_consum`
-- ----------------------------
BEGIN;
INSERT INTO `yl_class_consum` VALUES ('1', '2', '0', '1', '2.5', '1498752000', '34324', '1', '0');
COMMIT;

-- ----------------------------
--  Table structure for `yl_class_student`
-- ----------------------------
DROP TABLE IF EXISTS `yl_class_student`;
CREATE TABLE `yl_class_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_id` int(11) unsigned DEFAULT '0' COMMENT '学生id',
  `class_id` int(11) unsigned DEFAULT '0' COMMENT '班级id',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '1在班级2不在',
  `class_end` tinyint(1) unsigned DEFAULT '1' COMMENT '1上课2停课',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `yl_class_student`
-- ----------------------------
BEGIN;
INSERT INTO `yl_class_student` VALUES ('41', '3', '1', '1', '1'), ('42', '2', '6', '1', '1'), ('44', '3', '7', '1', '1'), ('47', '2', '7', '2', '1'), ('48', '4', '1', '1', '1');
COMMIT;

-- ----------------------------
--  Table structure for `yl_comments`
-- ----------------------------
DROP TABLE IF EXISTS `yl_comments`;
CREATE TABLE `yl_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_table` varchar(100) NOT NULL COMMENT '评论内容所在表，不带表前缀',
  `post_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论内容 id',
  `url` varchar(255) DEFAULT NULL COMMENT '原文地址',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '发表评论的用户id',
  `to_uid` int(11) NOT NULL DEFAULT '0' COMMENT '被评论的用户id',
  `full_name` varchar(50) DEFAULT NULL COMMENT '评论者昵称',
  `email` varchar(255) DEFAULT NULL COMMENT '评论者邮箱',
  `createtime` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '评论时间',
  `content` text NOT NULL COMMENT '评论内容',
  `type` smallint(1) NOT NULL DEFAULT '1' COMMENT '评论类型；1实名评论',
  `parentid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '被回复的评论id',
  `path` varchar(500) DEFAULT NULL,
  `status` smallint(1) NOT NULL DEFAULT '1' COMMENT '状态，1已审核，0未审核',
  PRIMARY KEY (`id`),
  KEY `comment_post_ID` (`post_id`),
  KEY `comment_approved_date_gmt` (`status`),
  KEY `comment_parent` (`parentid`),
  KEY `table_id_status` (`post_table`,`post_id`,`status`),
  KEY `createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
--  Table structure for `yl_common_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `yl_common_action_log`;
CREATE TABLE `yl_common_action_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` bigint(20) DEFAULT '0' COMMENT '用户id',
  `object` varchar(100) DEFAULT NULL COMMENT '访问对象的id,格式：不带前缀的表名+id;如posts1表示xx_posts表里id为1的记录',
  `action` varchar(50) DEFAULT NULL COMMENT '操作名称；格式规定为：应用名+控制器+操作名；也可自己定义格式只要不发生冲突且惟一；',
  `count` int(11) DEFAULT '0' COMMENT '访问次数',
  `last_time` int(11) DEFAULT '0' COMMENT '最后访问的时间戳',
  `ip` varchar(15) DEFAULT NULL COMMENT '访问者最后访问ip',
  PRIMARY KEY (`id`),
  KEY `user_object_action` (`user`,`object`,`action`),
  KEY `user_object_action_ip` (`user`,`object`,`action`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='访问记录表';

-- ----------------------------
--  Table structure for `yl_consume_hour`
-- ----------------------------
DROP TABLE IF EXISTS `yl_consume_hour`;
CREATE TABLE `yl_consume_hour` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `students_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '学生id',
  `consume_hour` varchar(20) DEFAULT NULL COMMENT '消耗课时',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT ' 0正常上课消耗1补课2缺课',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消耗时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '0默认未消耗 1已消耗',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学生消耗课时表';

-- ----------------------------
--  Table structure for `yl_finance`
-- ----------------------------
DROP TABLE IF EXISTS `yl_finance`;
CREATE TABLE `yl_finance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned DEFAULT '0' COMMENT '1学员收费2:学员退费3:市场支出4:人力支出5:其他',
  `source` tinyint(1) unsigned DEFAULT '0' COMMENT '0未知1进2出',
  `price` decimal(10,5) unsigned DEFAULT NULL COMMENT '金额',
  `project` text COMMENT '项目',
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  `contract_id` int(11) unsigned DEFAULT '0' COMMENT '合同id',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `is_del` tinyint(1) unsigned DEFAULT '0' COMMENT '1删除',
  `admin_id` int(11) unsigned DEFAULT '0' COMMENT '管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='财务表';

-- ----------------------------
--  Records of `yl_finance`
-- ----------------------------
BEGIN;
INSERT INTO `yl_finance` VALUES ('1', '1', '1', '8000.00000', '古筝课', '3', '2', '0', '0', '0', '0'), ('2', '1', '1', '0.00000', '', '0', '3', '0', '0', '0', '0'), ('3', '1', '1', '0.00000', '', '0', '4', '0', '0', '0', '0'), ('4', '1', '1', '0.00000', '', '0', '5', '0', '0', '0', '0'), ('5', '1', '1', '0.00000', '', '0', '6', '0', '0', '0', '0'), ('6', '1', '1', '0.00000', '', '0', '7', '0', '0', '0', '0'), ('7', '1', '1', '0.00000', '', '0', '8', '0', '0', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `yl_group_qrcode`
-- ----------------------------
DROP TABLE IF EXISTS `yl_group_qrcode`;
CREATE TABLE `yl_group_qrcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` varchar(100) DEFAULT '0' COMMENT '群id',
  `qrcode` varchar(255) DEFAULT NULL COMMENT '群二维码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='群二维码';

-- ----------------------------
--  Records of `yl_group_qrcode`
-- ----------------------------
BEGIN;
INSERT INTO `yl_group_qrcode` VALUES ('1', '3323233', '/qrcode/3323233.png'), ('2', '3323233222', '/qrcode/3323233222.png'), ('3', '20178875252737', '/qrcode/20178875252737.png'), ('4', '20181336260609', '/qrcode/20181336260609.png');
COMMIT;

-- ----------------------------
--  Table structure for `yl_guestbook`
-- ----------------------------
DROP TABLE IF EXISTS `yl_guestbook`;
CREATE TABLE `yl_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) NOT NULL COMMENT '留言者姓名',
  `email` varchar(100) NOT NULL COMMENT '留言者邮箱',
  `title` varchar(255) DEFAULT NULL COMMENT '留言标题',
  `msg` text NOT NULL COMMENT '留言内容',
  `createtime` int(11) unsigned NOT NULL COMMENT '留言时间',
  `status` smallint(2) NOT NULL DEFAULT '1' COMMENT '留言状态，1：正常，0：删除',
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='留言表';

-- ----------------------------
--  Records of `yl_guestbook`
-- ----------------------------
BEGIN;
INSERT INTO `yl_guestbook` VALUES ('1', '测试的你信吗？', '234234232qq.com', '真的测试的', '你不相信是测试的吗', '4294967295', '1', '0'), ('2', '测试的', '34234234234@', '标题测试的', '就是留言，i你信不信呢？', '1499739727', '1', '36'), ('3', '测试的', '34234234234@', '标题测试的', '就是留言，i你信不信呢？', '1499739737', '1', '36'), ('4', '测试的', '34234234234@', '标题测试的', '就是留言，i你信不信呢？', '1499739947', '1', '36'), ('5', '测试的', '34234234234@qq.com', '标题测试的', '就是留言，i你信不信呢？', '1499740111', '1', '36'), ('6', '测试的', '34234234234@qq.com', '标题测试的', '就是留言，i你信不信呢？', '1499740112', '1', '36'), ('7', '测试的', '34234234234@qq.com', '标题测试的', '就是留言，i你信不信呢？', '1499740112', '1', '36'), ('8', '测试的', '34234234234@qq.com', '标题测试的', '就是留言，i你信不信呢？', '1499740113', '1', '36'), ('9', '测试的', '34234234234@qq.com', '标题测试的', '就是留言，i你信不信呢？', '1499740113', '1', '36');
COMMIT;

-- ----------------------------
--  Table structure for `yl_license`
-- ----------------------------
DROP TABLE IF EXISTS `yl_license`;
CREATE TABLE `yl_license` (
  `li_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '授权id',
  `li_name` varchar(255) NOT NULL COMMENT '网站名称',
  `li_site` varchar(255) NOT NULL COMMENT '网站域名',
  `li_license` varchar(255) NOT NULL COMMENT '授权码内容',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1可用，0不可用',
  PRIMARY KEY (`li_id`),
  KEY `li_name` (`li_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `yl_license`
-- ----------------------------
BEGIN;
INSERT INTO `yl_license` VALUES ('1', '半青村', '120.25.254.69', '6a445241d4e2c5d84b81012086e846c4', '1');
COMMIT;

-- ----------------------------
--  Table structure for `yl_links`
-- ----------------------------
DROP TABLE IF EXISTS `yl_links`;
CREATE TABLE `yl_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL COMMENT '友情链接地址',
  `link_name` varchar(255) NOT NULL COMMENT '友情链接名称',
  `link_image` varchar(255) DEFAULT NULL COMMENT '友情链接图标',
  `link_target` varchar(25) NOT NULL DEFAULT '_blank' COMMENT '友情链接打开方式',
  `link_description` text NOT NULL COMMENT '友情链接描述',
  `link_status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  `link_rating` int(11) NOT NULL DEFAULT '0' COMMENT '友情链接评级',
  `link_rel` varchar(255) DEFAULT NULL COMMENT '链接与网站的关系',
  `listorder` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='友情链接表';

-- ----------------------------
--  Table structure for `yl_menu`
-- ----------------------------
DROP TABLE IF EXISTS `yl_menu`;
CREATE TABLE `yl_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `app` varchar(30) NOT NULL DEFAULT '' COMMENT '应用名称app',
  `model` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '操作名称',
  `data` varchar(50) NOT NULL DEFAULT '' COMMENT '额外参数',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单类型  1：权限认证+菜单；0：只作为菜单',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，1显示，0不显示',
  `name` varchar(50) NOT NULL COMMENT '菜单名称',
  `icon` varchar(50) DEFAULT NULL COMMENT '菜单图标',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `parentid` (`parentid`),
  KEY `model` (`model`)
) ENGINE=MyISAM AUTO_INCREMENT=205 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
--  Records of `yl_menu`
-- ----------------------------
BEGIN;
INSERT INTO `yl_menu` VALUES ('1', '0', 'Admin', 'Content', 'default', '', '0', '1', '内容管理', 'th', '', '3'), ('2', '0', 'Api', 'Guestbookadmin', 'index', '', '1', '1', '所有留言', '', '', '7'), ('3', '2', 'Api', 'Guestbookadmin', 'delete', '', '1', '0', '删除网站留言', '', '', '0'), ('4', '1', 'Comment', 'Commentadmin', 'index', '', '1', '1', '评论管理', '', '', '0'), ('5', '4', 'Comment', 'Commentadmin', 'delete', '', '1', '0', '删除评论', '', '', '0'), ('6', '4', 'Comment', 'Commentadmin', 'check', '', '1', '0', '评论审核', '', '', '0'), ('7', '1', 'Portal', 'AdminPost', 'index', '', '1', '1', '文章管理', '', '', '1'), ('8', '7', 'Portal', 'AdminPost', 'listorders', '', '1', '0', '文章排序', '', '', '0'), ('9', '7', 'Portal', 'AdminPost', 'top', '', '1', '0', '文章置顶', '', '', '0'), ('10', '7', 'Portal', 'AdminPost', 'recommend', '', '1', '0', '文章推荐', '', '', '0'), ('11', '7', 'Portal', 'AdminPost', 'move', '', '1', '0', '批量移动', '', '', '1000'), ('12', '7', 'Portal', 'AdminPost', 'check', '', '1', '0', '文章审核', '', '', '1000'), ('13', '7', 'Portal', 'AdminPost', 'delete', '', '1', '0', '删除文章', '', '', '1000'), ('14', '7', 'Portal', 'AdminPost', 'edit', '', '1', '0', '编辑文章', '', '', '1000'), ('15', '14', 'Portal', 'AdminPost', 'edit_post', '', '1', '0', '提交编辑', '', '', '0'), ('16', '7', 'Portal', 'AdminPost', 'add', '', '1', '0', '添加文章', '', '', '1000'), ('17', '16', 'Portal', 'AdminPost', 'add_post', '', '1', '0', '提交添加', '', '', '0'), ('18', '1', 'Portal', 'AdminTerm', 'index', '', '0', '1', '分类管理', '', '', '2'), ('19', '18', 'Portal', 'AdminTerm', 'listorders', '', '1', '0', '文章分类排序', '', '', '0'), ('20', '18', 'Portal', 'AdminTerm', 'delete', '', '1', '0', '删除分类', '', '', '1000'), ('21', '18', 'Portal', 'AdminTerm', 'edit', '', '1', '0', '编辑分类', '', '', '1000'), ('22', '21', 'Portal', 'AdminTerm', 'edit_post', '', '1', '0', '提交编辑', '', '', '0'), ('23', '18', 'Portal', 'AdminTerm', 'add', '', '1', '0', '添加分类', '', '', '1000'), ('24', '23', 'Portal', 'AdminTerm', 'add_post', '', '1', '0', '提交添加', '', '', '0'), ('25', '0', 'Portal', 'AdminPage', 'index', '', '1', '1', '公告管理', '', '', '6'), ('26', '25', 'Portal', 'AdminPage', 'listorders', '', '1', '0', '页面排序', '', '', '0'), ('27', '25', 'Portal', 'AdminPage', 'delete', '', '1', '0', '删除页面', '', '', '1000'), ('28', '25', 'Portal', 'AdminPage', 'edit', '', '1', '0', '编辑页面', '', '', '1000'), ('29', '28', 'Portal', 'AdminPage', 'edit_post', '', '1', '0', '提交编辑', '', '', '0'), ('30', '25', 'Portal', 'AdminPage', 'add', '', '1', '0', '添加公告', '', '', '1000'), ('31', '30', 'Portal', 'AdminPage', 'add_post', '', '1', '0', '提交添加', '', '', '0'), ('32', '1', 'Admin', 'Recycle', 'default', '', '1', '1', '回收站', '', '', '4'), ('33', '32', 'Portal', 'AdminPost', 'recyclebin', '', '1', '1', '文章回收', '', '', '0'), ('34', '33', 'Portal', 'AdminPost', 'restore', '', '1', '0', '文章还原', '', '', '1000'), ('35', '33', 'Portal', 'AdminPost', 'clean', '', '1', '0', '彻底删除', '', '', '1000'), ('36', '32', 'Portal', 'AdminPage', 'recyclebin', '', '1', '1', '页面回收', '', '', '1'), ('37', '36', 'Portal', 'AdminPage', 'clean', '', '1', '0', '彻底删除', '', '', '1000'), ('38', '36', 'Portal', 'AdminPage', 'restore', '', '1', '0', '页面还原', '', '', '1000'), ('40', '39', 'Admin', 'Backup', 'default', '', '1', '0', '备份管理', '', '', '0'), ('41', '40', 'Admin', 'Backup', 'restore', '', '1', '1', '数据还原', '', '', '0'), ('42', '40', 'Admin', 'Backup', 'index', '', '1', '1', '数据备份', '', '', '0'), ('43', '42', 'Admin', 'Backup', 'index_post', '', '1', '0', '提交数据备份', '', '', '0'), ('44', '40', 'Admin', 'Backup', 'download', '', '1', '0', '下载备份', '', '', '1000'), ('45', '40', 'Admin', 'Backup', 'del_backup', '', '1', '0', '删除备份', '', '', '1000'), ('46', '40', 'Admin', 'Backup', 'import', '', '1', '0', '数据备份导入', '', '', '1000'), ('194', '0', 'Admin', 'Student', 'default', '', '1', '1', '学生管理', '', '', '2'), ('195', '194', 'Admin', 'Student', 'index', '', '1', '1', '学生列表', '', '', '0'), ('196', '0', 'Admin', 'School', 'default', '', '1', '1', '学校管理管理', '', '', '3'), ('197', '0', 'Admin', 'Staff', 'default', '', '1', '1', '人事管理', '', '', '5'), ('198', '0', 'Admin', 'Class', 'default', '', '1', '1', '班级管理', '', '', '4'), ('199', '196', 'Admin', 'School', 'index', '', '1', '1', '学校列表', '', '', '0'), ('200', '197', 'Admin', 'Staff', 'index', '', '1', '1', '人事列表', '', '', '0'), ('201', '198', 'Admin', 'Class', 'index', '', '1', '1', '班级列表', '', '', '0'), ('202', '25', 'Portal', 'AdminPage', 'view', '', '1', '0', '公告详情', '', '', '0'), ('100', '109', 'Admin', 'Menu', 'index', '', '1', '1', '后台菜单', '', '', '21'), ('101', '100', 'Admin', 'Menu', 'add', '', '1', '0', '添加菜单', '', '', '0'), ('102', '101', 'Admin', 'Menu', 'add_post', '', '1', '0', '提交添加', '', '', '0'), ('103', '100', 'Admin', 'Menu', 'listorders', '', '1', '0', '后台菜单排序', '', '', '0'), ('104', '100', 'Admin', 'Menu', 'export_menu', '', '1', '0', '菜单备份', '', '', '1000'), ('105', '100', 'Admin', 'Menu', 'edit', '', '1', '0', '编辑菜单', '', '', '1000'), ('106', '105', 'Admin', 'Menu', 'edit_post', '', '1', '0', '提交编辑', '', '', '0'), ('107', '100', 'Admin', 'Menu', 'delete', '', '1', '0', '删除菜单', '', '', '1000'), ('108', '100', 'Admin', 'Menu', 'lists', '', '1', '0', '所有菜单', '', '', '1000'), ('109', '0', 'Admin', 'Setting', 'default', '', '0', '1', '设置', 'cogs', '', '0'), ('110', '109', 'Admin', 'Setting', 'userdefault', '', '0', '1', '个人信息', '', '', '0'), ('111', '110', 'Admin', 'User', 'userinfo', '', '1', '1', '修改信息', '', '', '0'), ('112', '111', 'Admin', 'User', 'userinfo_post', '', '1', '0', '修改信息提交', '', '', '0'), ('113', '110', 'Admin', 'Setting', 'password', '', '1', '1', '修改密码', '', '', '0'), ('114', '113', 'Admin', 'Setting', 'password_post', '', '1', '0', '提交修改', '', '', '0'), ('115', '109', 'Admin', 'Setting', 'site', '', '1', '1', '网站信息', '', '', '0'), ('116', '115', 'Admin', 'Setting', 'site_post', '', '1', '0', '提交修改', '', '', '0'), ('117', '115', 'Admin', 'Route', 'index', '', '1', '0', '路由列表', '', '', '0'), ('118', '115', 'Admin', 'Route', 'add', '', '1', '0', '路由添加', '', '', '0'), ('119', '118', 'Admin', 'Route', 'add_post', '', '1', '0', '路由添加提交', '', '', '0'), ('120', '115', 'Admin', 'Route', 'edit', '', '1', '0', '路由编辑', '', '', '0'), ('121', '120', 'Admin', 'Route', 'edit_post', '', '1', '0', '路由编辑提交', '', '', '0'), ('122', '115', 'Admin', 'Route', 'delete', '', '1', '0', '路由删除', '', '', '0'), ('123', '115', 'Admin', 'Route', 'ban', '', '1', '0', '路由禁止', '', '', '0'), ('124', '115', 'Admin', 'Route', 'open', '', '1', '0', '路由启用', '', '', '0'), ('125', '115', 'Admin', 'Route', 'listorders', '', '1', '0', '路由排序', '', '', '0'), ('126', '109', 'Admin', 'Mailer', 'default', '', '1', '1', '邮箱配置', '', '', '0'), ('127', '126', 'Admin', 'Mailer', 'index', '', '1', '1', 'SMTP配置', '', '', '0'), ('128', '127', 'Admin', 'Mailer', 'index_post', '', '1', '0', '提交配置', '', '', '0'), ('129', '126', 'Admin', 'Mailer', 'active', '', '1', '1', '注册邮件模板', '', '', '0'), ('130', '129', 'Admin', 'Mailer', 'active_post', '', '1', '0', '提交模板', '', '', '0'), ('131', '109', 'Admin', 'Setting', 'clearcache', '', '1', '1', '清除缓存', '', '', '1'), ('132', '0', 'Admin', 'admin', 'default', '', '1', '1', '管理组', 'group', '', '1'), ('133', '197', 'User', 'Indexadmin', 'default1', '', '1', '0', '用户组', '', '', '0'), ('134', '133', 'User', 'Indexadmin', 'index', '', '1', '1', '本站用户', 'leaf', '', '0'), ('135', '134', 'User', 'Indexadmin', 'ban', '', '1', '0', '拉黑会员', '', '', '0'), ('136', '134', 'User', 'Indexadmin', 'cancelban', '', '1', '0', '启用会员', '', '', '0'), ('137', '133', 'User', 'Oauthadmin', 'index', '', '1', '1', '第三方用户', 'leaf', '', '0'), ('138', '137', 'User', 'Oauthadmin', 'delete', '', '1', '0', '第三方用户解绑', '', '', '0'), ('139', '132', 'User', 'Indexadmin', 'default3', '', '1', '1', '管理组', '', '', '0'), ('140', '139', 'Admin', 'Rbac', 'index', '', '1', '1', '角色管理', '', '', '0'), ('141', '140', 'Admin', 'Rbac', 'member', '', '1', '0', '成员管理', '', '', '1000'), ('142', '140', 'Admin', 'Rbac', 'authorize', '', '1', '0', '权限设置', '', '', '1000'), ('143', '142', 'Admin', 'Rbac', 'authorize_post', '', '1', '0', '提交设置', '', '', '0'), ('144', '140', 'Admin', 'Rbac', 'roleedit', '', '1', '0', '编辑角色', '', '', '1000'), ('145', '144', 'Admin', 'Rbac', 'roleedit_post', '', '1', '0', '提交编辑', '', '', '0'), ('146', '140', 'Admin', 'Rbac', 'roledelete', '', '1', '1', '删除角色', '', '', '1000'), ('147', '140', 'Admin', 'Rbac', 'roleadd', '', '1', '1', '添加角色', '', '', '1000'), ('148', '147', 'Admin', 'Rbac', 'roleadd_post', '', '1', '0', '提交添加', '', '', '0'), ('149', '139', 'Admin', 'User', 'index', '', '1', '1', '管理员', '', '', '0'), ('150', '149', 'Admin', 'User', 'delete', '', '1', '0', '删除管理员', '', '', '1000'), ('151', '149', 'Admin', 'User', 'edit', '', '1', '0', '管理员编辑', '', '', '1000'), ('152', '151', 'Admin', 'User', 'edit_post', '', '1', '0', '编辑提交', '', '', '0'), ('153', '149', 'Admin', 'User', 'add', '', '1', '0', '管理员添加', '', '', '1000'), ('154', '153', 'Admin', 'User', 'add_post', '', '1', '0', '添加提交', '', '', '0'), ('156', '109', 'Admin', 'Storage', 'index', '', '1', '1', '文件存储', '', '', '0'), ('157', '156', 'Admin', 'Storage', 'setting_post', '', '1', '0', '文件存储设置提交', '', '', '0'), ('160', '149', 'Admin', 'User', 'ban', '', '1', '0', '禁用管理员', '', '', '0'), ('161', '149', 'Admin', 'User', 'cancelban', '', '1', '0', '启用管理员', '', '', '0'), ('166', '127', 'Admin', 'Mailer', 'test', '', '1', '0', '测试邮件', '', '', '0'), ('167', '109', 'Admin', 'Setting', 'upload', '', '1', '1', '上传设置', '', '', '0'), ('168', '167', 'Admin', 'Setting', 'upload_post', '', '1', '0', '上传设置提交', '', '', '0'), ('169', '7', 'Portal', 'AdminPost', 'copy', '', '1', '0', '文章批量复制', '', '', '0'), ('174', '100', 'Admin', 'Menu', 'backup_menu', '', '1', '0', '备份菜单', '', '', '0'), ('175', '100', 'Admin', 'Menu', 'export_menu_lang', '', '1', '0', '导出后台菜单多语言包', '', '', '0'), ('176', '100', 'Admin', 'Menu', 'restore_menu', '', '1', '0', '还原菜单', '', '', '0'), ('177', '100', 'Admin', 'Menu', 'getactions', '', '1', '0', '导入新菜单', '', '', '0'), ('187', '1', 'Admin', 'Site', 'index', '', '1', '1', '网站授权', '', '', '0'), ('188', '187', 'Admin', 'Site', 'toggle', '', '1', '0', '网站授权显示切换', '', '', '0'), ('189', '187', 'Admin', 'Site', 'delete', '', '1', '0', '删除授权', '', '', '0'), ('190', '187', 'Admin', 'Site', 'edit', '', '1', '0', '编辑授权', '', '', '0'), ('191', '190', 'Admin', 'Site', 'edit_post', '', '1', '0', '提交编辑', '', '', '0'), ('192', '187', 'Admin', 'Site', 'add', '', '1', '0', '添加授权', '', '', '0'), ('193', '192', 'Admin', 'Site', 'add_post', '', '1', '0', '提交添加', '', '', '0'), ('203', '194', 'Admin', 'Contract', 'index', '', '1', '1', '合同管理', '', '', '0'), ('204', '0', 'Admin', 'Finance', 'index', '', '1', '1', '财务管理', '', '', '8');
COMMIT;

-- ----------------------------
--  Table structure for `yl_oauth_user`
-- ----------------------------
DROP TABLE IF EXISTS `yl_oauth_user`;
CREATE TABLE `yl_oauth_user` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `from` varchar(20) NOT NULL COMMENT '用户来源key',
  `name` varchar(30) NOT NULL COMMENT '第三方昵称',
  `head_img` varchar(200) NOT NULL COMMENT '头像',
  `uid` int(20) NOT NULL COMMENT '关联的本站用户id',
  `create_time` datetime NOT NULL COMMENT '绑定时间',
  `last_login_time` datetime NOT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(16) NOT NULL COMMENT '最后登录ip',
  `login_times` int(6) NOT NULL COMMENT '登录次数',
  `status` tinyint(2) NOT NULL,
  `access_token` varchar(512) NOT NULL,
  `expires_date` int(11) NOT NULL COMMENT 'access_token过期时间',
  `openid` varchar(40) NOT NULL COMMENT '第三方用户id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='第三方用户表';

-- ----------------------------
--  Table structure for `yl_options`
-- ----------------------------
DROP TABLE IF EXISTS `yl_options`;
CREATE TABLE `yl_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL COMMENT '配置名',
  `option_value` longtext NOT NULL COMMENT '配置值',
  `autoload` int(2) NOT NULL DEFAULT '1' COMMENT '是否自动加载',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='全站配置表';

-- ----------------------------
--  Records of `yl_options`
-- ----------------------------
BEGIN;
INSERT INTO `yl_options` VALUES ('1', 'member_email_active', '{\"title\":\"WinLangCMS\\u90ae\\u4ef6\\u6fc0\\u6d3b\\u901a\\u77e5.\",\"template\":\"<p>\\u672c\\u90ae\\u4ef6\\u6765\\u81ea<a href=\\\"http:\\/\\/www.winlang.com\\\" target=\\\"_self\\\" textvalue=\\\"WinLangCMS\\\">WinLangCMS<\\/a><br\\/><br\\/>&nbsp; &nbsp;<strong>---------------<strong style=\\\"white-space: normal;\\\">---<\\/strong><\\/strong><br\\/>&nbsp; &nbsp;<strong>\\u5e10\\u53f7\\u6fc0\\u6d3b\\u8bf4\\u660e<\\/strong><br\\/>&nbsp; &nbsp;<strong>---------------<strong style=\\\"white-space: normal;\\\">---<\\/strong><\\/strong><br\\/><br\\/>&nbsp; &nbsp; \\u5c0a\\u656c\\u7684<span style=\\\"FONT-SIZE: 16px; FONT-FAMILY: Arial; COLOR: rgb(51,51,51); LINE-HEIGHT: 18px; BACKGROUND-COLOR: rgb(255,255,255)\\\">#username#\\uff0c\\u60a8\\u597d\\u3002<\\/span>\\u5982\\u679c\\u60a8\\u662fWinLang\\u7684\\u65b0\\u7528\\u6237\\uff0c\\u6216\\u5728\\u4fee\\u6539\\u60a8\\u7684\\u6ce8\\u518cEmail\\u65f6\\u4f7f\\u7528\\u4e86\\u672c\\u5730\\u5740\\uff0c\\u6211\\u4eec\\u9700\\u8981\\u5bf9\\u60a8\\u7684\\u5730\\u5740\\u6709\\u6548\\u6027\\u8fdb\\u884c\\u9a8c\\u8bc1\\u4ee5\\u907f\\u514d\\u5783\\u573e\\u90ae\\u4ef6\\u6216\\u5730\\u5740\\u88ab\\u6ee5\\u7528\\u3002<br\\/>&nbsp; &nbsp; \\u60a8\\u53ea\\u9700\\u70b9\\u51fb\\u4e0b\\u9762\\u7684\\u94fe\\u63a5\\u5373\\u53ef\\u6fc0\\u6d3b\\u60a8\\u7684\\u5e10\\u53f7\\uff1a<br\\/>&nbsp; &nbsp; <a title=\\\"\\\" href=\\\"http:\\/\\/#link#\\\" target=\\\"_self\\\">http:\\/\\/#link#<\\/a><br\\/>&nbsp; &nbsp; (\\u5982\\u679c\\u4e0a\\u9762\\u4e0d\\u662f\\u94fe\\u63a5\\u5f62\\u5f0f\\uff0c\\u8bf7\\u5c06\\u8be5\\u5730\\u5740\\u624b\\u5de5\\u7c98\\u8d34\\u5230\\u6d4f\\u89c8\\u5668\\u5730\\u5740\\u680f\\u518d\\u8bbf\\u95ee)<br\\/>&nbsp; &nbsp; \\u611f\\u8c22\\u60a8\\u7684\\u8bbf\\u95ee\\uff0c\\u795d\\u60a8\\u4f7f\\u7528\\u6109\\u5feb\\uff01<br\\/><br\\/>&nbsp; &nbsp; \\u6b64\\u81f4<br\\/>&nbsp; &nbsp; WinLang \\u7ba1\\u7406\\u56e2\\u961f.<\\/p>\"}', '1'), ('6', 'site_options', '{\"site_name\":\"crm\\u7ba1\\u7406\\u7cfb\\u7edf\",\"site_admin_url_password\":\"\",\"site_tpl\":\"simplebootx\",\"site_adminstyle\":\"bluesky\",\"site_icp\":\"\",\"site_admin_email\":\"admin@qq.com\",\"site_tongji\":\"\",\"site_copyright\":\"\",\"site_seo_title\":\"crm\\u7ba1\\u7406\\u7cfb\\u7edf\",\"site_seo_keywords\":\"crm\\u7ba1\\u7406\\u7cfb\\u7edf\",\"site_seo_description\":\"crm\\u7ba1\\u7406\\u7cfb\\u7edf\",\"urlmode\":\"2\",\"html_suffix\":\".html\",\"comment_time_interval\":\"60\"}', '1'), ('7', 'cmf_settings', '{\"banned_usernames\":\"\"}', '1'), ('8', 'cdn_settings', '{\"cdn_static_root\":\"\"}', '1');
COMMIT;

-- ----------------------------
--  Table structure for `yl_parents`
-- ----------------------------
DROP TABLE IF EXISTS `yl_parents`;
CREATE TABLE `yl_parents` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '家长姓名',
  `relationship` varchar(100) DEFAULT NULL COMMENT '与学员关系',
  `phone` varchar(100) DEFAULT NULL COMMENT '手机号码',
  `guardian` tinyint(1) unsigned DEFAULT '0' COMMENT '1监护人',
  `stu_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `yl_parents`
-- ----------------------------
BEGIN;
INSERT INTO `yl_parents` VALUES ('1', '家长555', '妈妈', '13809093333', '1', '4'), ('2', '家长22', '爸爸', '13809093334', '0', '4'), ('3', '', '', '', '0', '4'), ('4', 'sdsdf', 'sdf', '32312312', '0', '4');
COMMIT;

-- ----------------------------
--  Table structure for `yl_plugins`
-- ----------------------------
DROP TABLE IF EXISTS `yl_plugins`;
CREATE TABLE `yl_plugins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) NOT NULL COMMENT '插件名，英文',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '插件名称',
  `description` text COMMENT '插件描述',
  `type` tinyint(2) DEFAULT '0' COMMENT '插件类型, 1:网站；8;微信',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态；1开启；',
  `config` text COMMENT '插件配置',
  `hooks` varchar(255) DEFAULT NULL COMMENT '实现的钩子;以“，”分隔',
  `has_admin` tinyint(2) DEFAULT '0' COMMENT '插件是否有后台管理界面',
  `author` varchar(50) DEFAULT '' COMMENT '插件作者',
  `version` varchar(20) DEFAULT '' COMMENT '插件版本号',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '插件安装时间',
  `listorder` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
--  Table structure for `yl_posts`
-- ----------------------------
DROP TABLE IF EXISTS `yl_posts`;
CREATE TABLE `yl_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned DEFAULT '0' COMMENT '发表者id',
  `post_keywords` varchar(150) NOT NULL COMMENT 'seo keywords',
  `post_source` varchar(150) DEFAULT NULL COMMENT '转载文章的来源',
  `post_date` datetime DEFAULT '2000-01-01 00:00:00' COMMENT 'post发布日期',
  `post_content` longtext COMMENT 'post内容',
  `post_title` text COMMENT 'post标题',
  `post_excerpt` text COMMENT 'post摘要',
  `post_status` int(2) DEFAULT '1' COMMENT 'post状态，1已审核，0未审核,3删除',
  `comment_status` int(2) DEFAULT '1' COMMENT '评论状态，1允许，0不允许',
  `post_modified` datetime DEFAULT '2000-01-01 00:00:00' COMMENT 'post更新时间，可在前台修改，显示给用户',
  `post_content_filtered` longtext,
  `post_parent` bigint(20) unsigned DEFAULT '0' COMMENT 'post的父级post id,表示post层级关系',
  `post_type` int(2) DEFAULT '1' COMMENT 'post类型，1文章,2页面',
  `post_mime_type` varchar(100) DEFAULT '',
  `comment_count` bigint(20) DEFAULT '0',
  `smeta` text COMMENT 'post的扩展字段，保存相关扩展属性，如缩略图；格式为json',
  `post_hits` int(11) DEFAULT '0' COMMENT 'post点击数，查看数',
  `post_like` int(11) DEFAULT '0' COMMENT 'post赞数',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶 1置顶； 0不置顶',
  `recommended` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐 1推荐 0不推荐',
  `school` tinyint(1) unsigned DEFAULT '0' COMMENT '所属学校',
  PRIMARY KEY (`id`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`id`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`),
  KEY `post_date` (`post_date`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Portal文章表';

-- ----------------------------
--  Records of `yl_posts`
-- ----------------------------
BEGIN;
INSERT INTO `yl_posts` VALUES ('1', '1', '测试的', null, '2017-07-07 16:38:15', '<p>测试的</p>', '测试的', '测试的', '1', '1', '2017-07-07 16:35:04', null, '0', '2', '', '0', '{\"thumb\":\"\"}', '0', '0', '0', '0', '0'), ('2', '1', '测试公告2', null, '2017-07-07 16:40:15', '<p>测试公告2</p>', '测试公告2', '测试公告2', '1', '1', '2017-07-07 16:39:56', null, '0', '2', '', '0', '{\"thumb\":\"\"}', '0', '0', '0', '0', '1');
COMMIT;

-- ----------------------------
--  Table structure for `yl_push_message`
-- ----------------------------
DROP TABLE IF EXISTS `yl_push_message`;
CREATE TABLE `yl_push_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '消息标题',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '消息内容',
  `created_time` int(10) NOT NULL DEFAULT '0' COMMENT '消息创建时间',
  `status` int(1) NOT NULL COMMENT '0:未发送1发送成功',
  `delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未删除 1:已删除',
  `send_user_id` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1提前上课通知2通知老师上课3通知家长上课4缺课提醒',
  `read_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未读1已读',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='推送消息';

-- ----------------------------
--  Table structure for `yl_refund_table`
-- ----------------------------
DROP TABLE IF EXISTS `yl_refund_table`;
CREATE TABLE `yl_refund_table` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL COMMENT '退费项目名',
  `price` decimal(30,2) DEFAULT NULL,
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1退费2续费',
  `stu_id` int(11) unsigned DEFAULT '0' COMMENT '学生id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学生退费';

-- ----------------------------
--  Table structure for `yl_role`
-- ----------------------------
DROP TABLE IF EXISTS `yl_role`;
CREATE TABLE `yl_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '角色名称',
  `pid` smallint(6) DEFAULT NULL COMMENT '父角色ID',
  `status` tinyint(1) unsigned DEFAULT NULL COMMENT '状态',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorder` int(3) NOT NULL DEFAULT '0' COMMENT '排序字段',
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
--  Records of `yl_role`
-- ----------------------------
BEGIN;
INSERT INTO `yl_role` VALUES ('1', '超级管理员', '0', '1', '拥有网站最高管理员权限！', '1329633709', '1329633709', '0'), ('2', '学校管理员', null, '1', '学校管理员', '1496917626', '1498446340', '0'), ('3', '学校经营者', null, '1', '', '1498548409', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `yl_role_user`
-- ----------------------------
DROP TABLE IF EXISTS `yl_role_user`;
CREATE TABLE `yl_role_user` (
  `role_id` int(11) unsigned DEFAULT '0' COMMENT '角色 id',
  `user_id` int(11) DEFAULT '0' COMMENT '用户id',
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色对应表';

-- ----------------------------
--  Records of `yl_role_user`
-- ----------------------------
BEGIN;
INSERT INTO `yl_role_user` VALUES ('2', '2'), ('3', '3'), ('3', '4'), ('2', '5');
COMMIT;

-- ----------------------------
--  Table structure for `yl_route`
-- ----------------------------
DROP TABLE IF EXISTS `yl_route`;
CREATE TABLE `yl_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '路由id',
  `full_url` varchar(255) DEFAULT NULL COMMENT '完整url， 如：portal/list/index?id=1',
  `url` varchar(255) DEFAULT NULL COMMENT '实际显示的url',
  `listorder` int(5) DEFAULT '0' COMMENT '排序，优先级，越小优先级越高',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态，1：启用 ;0：不启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='url路由表';

-- ----------------------------
--  Table structure for `yl_school`
-- ----------------------------
DROP TABLE IF EXISTS `yl_school`;
CREATE TABLE `yl_school` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `name` varchar(100) NOT NULL COMMENT '学校名称',
  `manage` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '学校拥有者',
  `listorder` int(5) unsigned DEFAULT '0' COMMENT '排序',
  `auth_user` int(11) DEFAULT '0' COMMENT '权限管理者',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_name` (`name`),
  KEY `idx_manage` (`manage`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='学校列表';

-- ----------------------------
--  Records of `yl_school`
-- ----------------------------
BEGIN;
INSERT INTO `yl_school` VALUES ('1', '1498549349', '1498549349', '发送', '0', '1', '0'), ('2', '1498550596', '1498551583', '2222测试的', '0', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `yl_sms_code`
-- ----------------------------
DROP TABLE IF EXISTS `yl_sms_code`;
CREATE TABLE `yl_sms_code` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '注册码编号',
  `code` char(20) NOT NULL DEFAULT '0' COMMENT '验证码',
  `phone` char(12) NOT NULL DEFAULT '0' COMMENT '手机号码',
  `delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未删除 1:已删除',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:找回密码 2:修改密码3:账户激活4:注册用户',
  `success` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未成功 1:已成功',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未使用 1:已使用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='短信验证码';

-- ----------------------------
--  Records of `yl_sms_code`
-- ----------------------------
BEGIN;
INSERT INTO `yl_sms_code` VALUES ('1', '4920', '18602552458', '0', '4', '1', '1'), ('7', '2569', '17512536980', '0', '4', '1', '1'), ('9', '4568', '18602552458', '0', '1', '1', '0'), ('4', '1774', '18952025303', '0', '4', '1', '0'), ('8', '6331', '18994725813', '0', '4', '1', '1'), ('6', '6379', '15617567297', '0', '4', '1', '1'), ('10', '5738', '18952025306', '0', '4', '1', '1'), ('11', '3730', '18952025306', '0', '4', '1', '1');
COMMIT;

-- ----------------------------
--  Table structure for `yl_staff`
-- ----------------------------
DROP TABLE IF EXISTS `yl_staff`;
CREATE TABLE `yl_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表主键',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '更新时间',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别1男2女',
  `position` int(11) DEFAULT NULL COMMENT '职位',
  `phone` varchar(50) NOT NULL DEFAULT '0' COMMENT '电话',
  `identity_cards` varchar(50) NOT NULL DEFAULT '0' COMMENT '身份证',
  `address` varchar(255) DEFAULT NULL COMMENT '家庭住址',
  `emergency_contact` varchar(50) DEFAULT NULL COMMENT '紧急联系人姓名',
  `emergency_call` varchar(50) DEFAULT NULL COMMENT '紧急联系电话',
  `school_id` int(11) NOT NULL DEFAULT '0' COMMENT '所在学校',
  `number` varchar(100) DEFAULT NULL COMMENT '编号',
  PRIMARY KEY (`id`),
  KEY `idx_sex` (`sex`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='各学校人事管理';

-- ----------------------------
--  Records of `yl_staff`
-- ----------------------------
BEGIN;
INSERT INTO `yl_staff` VALUES ('1', '1498553759', '1498633618', '水果222', '1', '3', '13909090909', '3209241990093420', '你就是的地方撒风', '测试', '13876767676', '1', '3212312'), ('2', '1498641547', '1498641547', '老师1', '1', '1', '13989898989', '32898989990000000', '测试的的', '测试地', '17509090909', '2', '232323');
COMMIT;

-- ----------------------------
--  Table structure for `yl_staff_position`
-- ----------------------------
DROP TABLE IF EXISTS `yl_staff_position`;
CREATE TABLE `yl_staff_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '职务名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `yl_staff_position`
-- ----------------------------
BEGIN;
INSERT INTO `yl_staff_position` VALUES ('1', '教师'), ('2', '教务'), ('3', '课程顾问'), ('4', '市场');
COMMIT;

-- ----------------------------
--  Table structure for `yl_student_contract`
-- ----------------------------
DROP TABLE IF EXISTS `yl_student_contract`;
CREATE TABLE `yl_student_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total_price` decimal(10,5) DEFAULT '0.00000' COMMENT '合同总价',
  `price` decimal(10,5) DEFAULT NULL COMMENT '单价',
  `start_time` int(11) unsigned DEFAULT '0' COMMENT '合同开始时间',
  `end_time` int(11) unsigned DEFAULT '0' COMMENT '合同结束时间',
  `class_number` varchar(100) DEFAULT NULL COMMENT '课时数',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `admin_id` int(11) unsigned DEFAULT '0' COMMENT '管理员id',
  `stu_id` int(11) unsigned DEFAULT '0' COMMENT '学生id',
  `name` varchar(100) DEFAULT NULL COMMENT '合同名称',
  `is_del` tinyint(1) unsigned DEFAULT '0' COMMENT '1删除',
  `update_admin` int(11) unsigned DEFAULT '0' COMMENT '做更新操作的管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='学员缴费合同';

-- ----------------------------
--  Records of `yl_student_contract`
-- ----------------------------
BEGIN;
INSERT INTO `yl_student_contract` VALUES ('1', '4000.00000', '200.00000', '1499788800', '1501430400', '20', '1499842855', '1499848180', '1', '2', '钢琴课程2', '1', '1'), ('2', '8000.00000', null, '1499788800', '1499788800', '40', '1499847266', '1499851149', '1', '3', '古筝课', '0', '1'), ('3', '0.00000', null, '0', '0', '', '1499851755', '1499851755', '1', '0', '', '1', '0'), ('4', '0.00000', null, '0', '0', '', '1499851987', '1499851987', '1', '0', '', '1', '0'), ('5', '0.00000', null, '0', '0', '', '1499852260', '1499852260', '1', '0', '', '1', '0'), ('6', '0.00000', null, '0', '0', '', '1499852316', '1499852316', '1', '0', '', '1', '0'), ('7', '0.00000', null, '0', '0', '', '1499852609', '1499852609', '1', '0', '', '1', '0'), ('8', '0.00000', null, '0', '0', '', '1499852663', '1499852663', '1', '0', '', '1', '0');
COMMIT;

-- ----------------------------
--  Table structure for `yl_students`
-- ----------------------------
DROP TABLE IF EXISTS `yl_students`;
CREATE TABLE `yl_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) NOT NULL COMMENT '学生姓名',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1男2女',
  `age` int(11) unsigned NOT NULL DEFAULT '0' COMMENT ' 年龄根据生日自动计算',
  `number` char(20) NOT NULL DEFAULT '0' COMMENT '学生编号',
  `mobile` char(20) NOT NULL DEFAULT '0' COMMENT '学生联系号码',
  `tuition` decimal(30,2) DEFAULT NULL COMMENT '学费',
  `class_hour` varchar(20) DEFAULT NULL COMMENT '购买课时',
  `unit_price` decimal(30,2) unsigned DEFAULT NULL COMMENT '单次课时价格',
  `consume_hour` varchar(20) DEFAULT NULL COMMENT '已消耗课时',
  `surplus_hour` varchar(20) DEFAULT NULL COMMENT '剩余课时',
  `class` int(11) unsigned DEFAULT NULL COMMENT '所在班级',
  `apply_date` int(11) unsigned DEFAULT '0' COMMENT '报名日期',
  `course_consultant` int(11) unsigned DEFAULT '0' COMMENT '课程顾问',
  `fingerprint` varchar(255) DEFAULT NULL COMMENT '指纹',
  `kindergarten` varchar(100) DEFAULT NULL COMMENT '所在幼儿园',
  `hire_purchase` tinyint(1) unsigned DEFAULT '0' COMMENT '是否分期默认0为否，1是',
  `time_consuming_reminder` varchar(20) DEFAULT NULL COMMENT '自定义消耗课时提醒',
  `lecture_setting` varchar(20) DEFAULT NULL COMMENT '消耗多少课时后停课（分期的情况）',
  `birthday` int(11) unsigned DEFAULT '0' COMMENT '出生日期',
  `address` varchar(255) DEFAULT NULL COMMENT '住址',
  `class_end` tinyint(1) unsigned DEFAULT '0' COMMENT '1停课',
  `add_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `card_info` varchar(100) DEFAULT NULL COMMENT '卡片信息',
  `school` int(11) unsigned DEFAULT '0' COMMENT '所属学校',
  `parent_list` text COMMENT '家长姓名信息',
  `course` varchar(50) DEFAULT NULL COMMENT '课程名称',
  `parent_id` varchar(255) DEFAULT '0' COMMENT '家长',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '1注销',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='学生信息表';

-- ----------------------------
--  Records of `yl_students`
-- ----------------------------
BEGIN;
INSERT INTO `yl_students` VALUES ('2', '王建', '1', '23', '234234234', '18989898989', '8000.00', '40', '200.00', '2', '38', '6', '1497974400', '1', '', '游府西街', '1', '18', '11', '1498060800', '测试的', '0', '0', '0', '12312312312', '2', '', '', '0', '0'), ('3', '测试', '2', '11', '123123123', '18990909090', '8000.00', '40', '200.00', '2', '38', '7', '1497974400', '1', '', '雨花幼儿园', '0', '11', '11', '1498060800', '测试地址', '0', '0', '0', '231212312', '0', '[{\"name\":\"\\u6d4b\\u8bd51\",\"phone\":\"17509090909\",\"relationship\":\"\\u5988\\u5988\",\"guardian\":1},{\"name\":\"\\u6d4b\\u8bd52\",\"phone\":\"17809090901\",\"relationship\":\"\\u7238\\u7238\",\"guardian\":0}]', '', '0', '0'), ('4', '策四', '1', '10', '34234', '18902552458', '8000.00', '10', '800.00', null, null, '1', '1501084800', '1', '', '测试等', '1', '8', '6', '1499270400', '测试地址看看', '0', '0', '0', '34234234', '2', null, '古筝', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `yl_term_relationships`
-- ----------------------------
DROP TABLE IF EXISTS `yl_term_relationships`;
CREATE TABLE `yl_term_relationships` (
  `tid` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'posts表里文章id',
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `listorder` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1发布，0不发布',
  PRIMARY KEY (`tid`),
  KEY `term_taxonomy_id` (`term_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Portal 文章分类对应表';

-- ----------------------------
--  Table structure for `yl_terms`
-- ----------------------------
DROP TABLE IF EXISTS `yl_terms`;
CREATE TABLE `yl_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `name` varchar(200) DEFAULT NULL COMMENT '分类名称',
  `slug` varchar(200) DEFAULT '',
  `taxonomy` varchar(32) DEFAULT NULL COMMENT '分类类型',
  `description` longtext COMMENT '分类描述',
  `parent` bigint(20) unsigned DEFAULT '0' COMMENT '分类父id',
  `count` bigint(20) DEFAULT '0' COMMENT '分类文章数',
  `path` varchar(500) DEFAULT NULL COMMENT '分类层级关系路径',
  `seo_title` varchar(500) DEFAULT NULL,
  `seo_keywords` varchar(500) DEFAULT NULL,
  `seo_description` varchar(500) DEFAULT NULL,
  `list_tpl` varchar(50) DEFAULT NULL COMMENT '分类列表模板',
  `one_tpl` varchar(50) DEFAULT NULL COMMENT '分类文章页模板',
  `listorder` int(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1发布，0不发布',
  PRIMARY KEY (`term_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Portal 文章分类表';

-- ----------------------------
--  Records of `yl_terms`
-- ----------------------------
BEGIN;
INSERT INTO `yl_terms` VALUES ('1', '列表演示', '', 'article', '', '0', '0', '0-1', '', '', '', 'list', 'article', '0', '1'), ('2', '瀑布流', '', 'article', '', '0', '0', '0-2', '', '', '', 'list_masonry', 'article', '0', '1');
COMMIT;

-- ----------------------------
--  Table structure for `yl_user_favorites`
-- ----------------------------
DROP TABLE IF EXISTS `yl_user_favorites`;
CREATE TABLE `yl_user_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) DEFAULT NULL COMMENT '用户 id',
  `title` varchar(255) DEFAULT NULL COMMENT '收藏内容的标题',
  `url` varchar(255) DEFAULT NULL COMMENT '收藏内容的原文地址，不带域名',
  `description` varchar(500) DEFAULT NULL COMMENT '收藏内容的描述',
  `table` varchar(50) DEFAULT NULL COMMENT '收藏实体以前所在表，不带前缀',
  `object_id` int(11) DEFAULT NULL COMMENT '收藏内容原来的主键id',
  `createtime` int(11) DEFAULT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户收藏表';

-- ----------------------------
--  Table structure for `yl_user_video`
-- ----------------------------
DROP TABLE IF EXISTS `yl_user_video`;
CREATE TABLE `yl_user_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL COMMENT '视频标题',
  `describe` varchar(255) DEFAULT NULL COMMENT '视频简介',
  `user_id` int(11) DEFAULT '0' COMMENT '用户id',
  `path` varchar(255) DEFAULT NULL COMMENT '视频地址',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '1删除',
  `cover_image` varchar(255) DEFAULT NULL COMMENT '视频封面',
  `group_id` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='用户上传视频表';

-- ----------------------------
--  Records of `yl_user_video`
-- ----------------------------
BEGIN;
INSERT INTO `yl_user_video` VALUES ('8', '测试的', '简介', '36', '/video/201707101612463110.mp4', '1499674366', '1499674366', '0', null, '3323233'), ('9', '测试的', '简介', '36', '/video/201707101613127714.mp4', '1499674392', '1499674392', '0', null, '3323233,111111,222222,333333'), ('10', '哈喽', '您婆婆 Mr', '37', '/video/201707111055095365.mp4', '1499741709', '1499741709', '0', null, '');
COMMIT;

-- ----------------------------
--  Table structure for `yl_users`
-- ----------------------------
DROP TABLE IF EXISTS `yl_users`;
CREATE TABLE `yl_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_pass` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码；sp_password加密',
  `user_nicename` varchar(50) NOT NULL DEFAULT '' COMMENT '用户美名',
  `user_email` varchar(100) NOT NULL DEFAULT '' COMMENT '登录邮箱',
  `user_url` varchar(100) NOT NULL DEFAULT '' COMMENT '用户个人网站',
  `avatar` varchar(255) DEFAULT NULL COMMENT '用户头像，相对于upload/avatar目录',
  `sex` smallint(1) DEFAULT '0' COMMENT '性别；0：保密，1：男；2：女',
  `birthday` date DEFAULT '2000-01-01' COMMENT '生日',
  `signature` varchar(255) DEFAULT NULL COMMENT '个性签名',
  `last_login_ip` varchar(16) DEFAULT NULL COMMENT '最后登录ip',
  `last_login_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '最后登录时间',
  `create_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '注册时间',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '' COMMENT '激活码',
  `user_status` int(11) NOT NULL DEFAULT '1' COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `user_type` smallint(1) DEFAULT '1' COMMENT '用户类型，1:admin ;2:会员',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '金币',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  PRIMARY KEY (`id`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
--  Records of `yl_users`
-- ----------------------------
BEGIN;
INSERT INTO `yl_users` VALUES ('1', 'admin', '###b4e9dfe53b64bec91d9ec950e28afd1a', 'admin', 'admin@qq.com', 'www.winlang.com', null, '0', '1987-09-05', '', '0.0.0.0', '2017-07-12 10:24:30', '2017-05-05 02:27:48', '', '1', '0', '1', '0', ''), ('2', 'admin1', '###dbe1665ac012ca2348c0706936a25f89', '', 'sadda@qq.com', '', null, '0', '2000-01-01', null, '0.0.0.0', '2017-06-26 11:08:00', '2017-06-08 18:17:43', '', '1', '0', '1', '0', ''), ('3', 'admin2', '###dbe1665ac012ca2348c0706936a25f89', '', 'admin2@qq.com', '', null, '0', '2000-01-01', null, null, '2000-01-01 00:00:00', '2017-06-27 15:27:20', '', '1', '0', '1', '0', ''), ('4', 'admin3', '###dbe1665ac012ca2348c0706936a25f89', '', 'admin3@qq.com', '', null, '0', '2000-01-01', null, null, '2000-01-01 00:00:00', '2017-06-27 15:27:47', '', '1', '0', '1', '0', ''), ('5', 'admin4', '###dbe1665ac012ca2348c0706936a25f89', '', 'admin4@qq.com', '', null, '0', '2000-01-01', null, null, '2000-01-01 00:00:00', '2017-06-27 15:28:00', '', '1', '0', '1', '0', '');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
