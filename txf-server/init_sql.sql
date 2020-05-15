/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50722
 Source Host           : localhost
 Source Database       : txf

 Target Server Type    : MySQL
 Target Server Version : 50722
 File Encoding         : utf-8

 Date: 02/27/2020 17:50:09 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `txf_admin`
-- ----------------------------
DROP TABLE IF EXISTS `txf_admin`;
CREATE TABLE `txf_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '用户名',
  `mobile` char(11) NOT NULL COMMENT '手机',
  `password` char(32) NOT NULL COMMENT '密码',
  `salt` char(32) NOT NULL COMMENT '盐值',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `login_error_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录失败次数',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='后台用户表';

-- ----------------------------
--  Records of `txf_admin`
-- ----------------------------
BEGIN;
INSERT INTO `txf_admin` VALUES ('1', 'admin', '13888888888', '6d0bf59ddef7801fbea391f5fd309554', 'd516f65913c76e934945834de9586b61', '1', '2020-02-27 15:36:51', '0', '2020-02-25 16:40:44', '2020-02-27 15:36:51');
COMMIT;

-- ----------------------------
--  Table structure for `txf_admin_role`
-- ----------------------------
DROP TABLE IF EXISTS `txf_admin_role`;
CREATE TABLE `txf_admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL COMMENT '后台用户ID',
  `role_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台用户、角色关联表';

-- ----------------------------
--  Table structure for `txf_del_bak`
-- ----------------------------
DROP TABLE IF EXISTS `txf_del_bak`;
CREATE TABLE `txf_del_bak` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tb_name` varchar(255) NOT NULL COMMENT '表名',
  `data` text NOT NULL COMMENT '删除的数据',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='删除数据备份表';

-- ----------------------------
--  Table structure for `txf_permission`
-- ----------------------------
DROP TABLE IF EXISTS `txf_permission`;
CREATE TABLE `txf_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL COMMENT '父ID',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:一级菜单 2:二级菜单 3:三级及以上菜单 4:操作(不显示在菜单栏)',
  `icon` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单ICON',
  `title` varchar(255) NOT NULL COMMENT '名称',
  `index` varchar(255) NOT NULL DEFAULT '' COMMENT '前端路由',
  `uri` varchar(255) NOT NULL DEFAULT '' COMMENT '对应请求路径',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `sort` int(11) NOT NULL DEFAULT '255' COMMENT '排序',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COMMENT='后台权限表';

-- ----------------------------
--  Records of `txf_permission`
-- ----------------------------
BEGIN;
INSERT INTO `txf_permission` VALUES ('1', '0', '1', 'el-icon-lx-settings', '系统设置', '', '', '1', '0', '2020-02-25 16:40:44', '2020-02-25 16:40:44'), ('2', '1', '2', '', '权限管理', 'permission', '/permission/lists', '1', '0', '2020-02-25 16:40:44', '2020-02-25 16:40:44'), ('3', '1', '2', '', '员工管理', 'staff', '/admin/lists', '1', '2', '2020-02-25 16:40:44', '2020-02-25 16:40:44'), ('4', '1', '2', '', '角色管理', 'role', '/role/lists', '1', '1', '2020-02-25 16:40:44', '2020-02-25 16:40:44'), ('5', '2', '4', '', '删除权限', '', '/permission/batchDelete', '1', '255', '2020-02-27 15:28:20', '2020-02-27 15:28:20'), ('6', '2', '4', '', '修改权限', '', '/permission/update', '1', '256', '2020-02-27 15:29:04', '2020-02-27 17:45:02'), ('7', '2', '4', '', '新增权限', '', '/permission/insert', '1', '255', '2020-02-27 15:31:25', '2020-02-27 15:31:25'), ('8', '2', '4', '', '权限下拉数据', '', '/permission/getPermissionCascader', '1', '255', '2020-02-27 15:40:04', '2020-02-27 15:40:48'), ('9', '3', '4', '', '删除员工', '', '/admin/batchDelete', '1', '255', '2020-02-27 15:42:13', '2020-02-27 15:42:13'), ('10', '3', '4', '', '修改员工', '', '/admin/update', '1', '256', '2020-02-27 15:43:06', '2020-02-27 15:43:42'), ('11', '3', '4', '', '新增员工', '', '/admin/insert', '1', '257', '2020-02-27 15:43:37', '2020-02-27 17:45:09'), ('12', '3', '4', '', '绑定角色', '', '/role/getRolesWithAdmin', '1', '255', '2020-02-27 15:50:33', '2020-02-27 15:51:35'), ('13', '3', '4', '', '绑定角色保存', '', '/admin/saveRoles', '1', '255', '2020-02-27 15:52:10', '2020-02-27 15:52:10'), ('14', '4', '4', '', '删除角色', '', '/role/batchDelete', '1', '255', '2020-02-27 15:53:33', '2020-02-27 15:53:33'), ('15', '4', '4', '', '修改角色', '', '/role/update', '1', '256', '2020-02-27 15:53:49', '2020-02-27 17:45:11'), ('16', '4', '4', '', '新增角色', '', '/role/insert', '1', '255', '2020-02-27 15:54:12', '2020-02-27 17:45:12'), ('17', '4', '4', '', '绑定权限', '', '/permission/getPermissionsWithRole', '1', '255', '2020-02-27 15:55:12', '2020-02-27 15:55:12'), ('18', '4', '4', '', '保存绑定权限', '', '/role/savePermissions', '1', '255', '2020-02-27 15:55:40', '2020-02-27 17:45:14'), ('19', '0', '1', 'el-icon-lx-home', '系统首页', 'dashboard', '/index/index', '1', '9999', '2020-02-27 15:03:24', '2020-02-27 15:03:24'), ('20', '19', '4', '', '退出登录', '', '/public/loginOut', '1', '255', '2020-02-27 16:00:10', '2020-02-27 16:00:10'), ('21', '19', '4', '', '更改密码', '', '/admin/updatePassword', '1', '255', '2020-02-27 16:00:48', '2020-02-27 17:45:19'), ('22', '19', '4', '', '导入EXCEL', '', '/public/uploadExample', '1', '255', '2020-02-27 16:01:10', '2020-02-27 17:45:20'), ('23', '19', '4', '', '导出EXCEL', '', '/public/downloadExample', '1', '255', '2020-02-27 16:01:35', '2020-02-27 17:45:22'), ('24', '19', '4', '', '如果你只想绑定系统首页，就新增我', '', '', '1', '255', '2020-02-27 17:47:21', '2020-02-27 17:49:35');
COMMIT;

-- ----------------------------
--  Table structure for `txf_role`
-- ----------------------------
DROP TABLE IF EXISTS `txf_role`;
CREATE TABLE `txf_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL COMMENT '角色名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '角色描述',
  `status` tinyint(4) NOT NULL COMMENT '角色状态',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='后台角色表';

-- ----------------------------
--  Table structure for `txf_role_permission`
-- ----------------------------
DROP TABLE IF EXISTS `txf_role_permission`;
CREATE TABLE `txf_role_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `permission_id` int(10) unsigned NOT NULL COMMENT '权限ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台 角色、权限关联表';

SET FOREIGN_KEY_CHECKS = 1;
