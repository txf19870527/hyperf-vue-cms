/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 80020
 Source Host           : 127.0.0.1:3306
 Source Schema         : txf

 Target Server Type    : MySQL
 Target Server Version : 80020
 File Encoding         : 65001

 Date: 30/01/2021 13:45:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for txf_admin
-- ----------------------------
DROP TABLE IF EXISTS `txf_admin`;
CREATE TABLE `txf_admin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `mobile` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '手机',
  `password` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码',
  `salt` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '盐值',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '状态',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `login_error_times` int unsigned NOT NULL DEFAULT '0' COMMENT '登录失败次数',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `mobile` (`mobile`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='后台用户表';

-- ----------------------------
-- Records of txf_admin
-- ----------------------------
BEGIN;
INSERT INTO `txf_admin` VALUES (1, '超级管理员', '13888888888', 'da26f921e8c1364d5a92b5c3319dfd20', 'b18d510e112c32df3b5cc84a0a3d893a', 1, '2021-01-30 13:43:46', 0, '2020-02-25 16:40:44', '2021-01-30 13:43:46');
INSERT INTO `txf_admin` VALUES (14, '访客', '13000000000', '756a2a8430bb8e9261a5f985f79a3d20', '0029c58b8f038851b15d133359d7e0c4', 1, '2021-01-30 13:44:54', 0, '2021-01-29 21:42:49', '2021-01-30 13:44:54');
COMMIT;

-- ----------------------------
-- Table structure for txf_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `txf_admin_role`;
CREATE TABLE `txf_admin_role` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int unsigned NOT NULL COMMENT '后台用户ID',
  `role_id` int unsigned NOT NULL COMMENT '角色ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `admin_id` (`admin_id`) USING BTREE,
  KEY `role_id` (`role_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='后台用户、角色关联表';

-- ----------------------------
-- Records of txf_admin_role
-- ----------------------------
BEGIN;
INSERT INTO `txf_admin_role` VALUES (3, 14, 1, '2021-01-30 13:42:27', '2021-01-30 13:42:27');
COMMIT;

-- ----------------------------
-- Table structure for txf_del_bak
-- ----------------------------
DROP TABLE IF EXISTS `txf_del_bak`;
CREATE TABLE `txf_del_bak` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tb_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '表名',
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '删除的数据',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='删除数据备份表';

-- ----------------------------
-- Records of txf_del_bak
-- ----------------------------
BEGIN;
INSERT INTO `txf_del_bak` VALUES (1, 'admin', '{\"id\":15,\"name\":\"test\",\"mobile\":\"13585530822\",\"password\":\"36ec892449c7008b673ec0be0d88bcc7\",\"salt\":\"27e83e25bc86c22ecf388849050a1b96\",\"status\":1,\"last_login_time\":null,\"login_error_times\":0,\"created_at\":\"2021-01-30 13:02:52\",\"updated_at\":\"2021-01-30 13:02:52\"}', '2021-01-30 13:02:57');
INSERT INTO `txf_del_bak` VALUES (2, 'admin', '{\"id\":16,\"name\":\"test1\",\"mobile\":\"13585530881\",\"password\":\"5e24d7a93e494dbca0b744321272ee62\",\"salt\":\"eda5ac82165882dce0174b6fe2f3d7d0\",\"status\":0,\"last_login_time\":null,\"login_error_times\":1,\"created_at\":\"2021-01-30 13:03:04\",\"updated_at\":\"2021-01-30 13:03:12\"}', '2021-01-30 13:03:26');
INSERT INTO `txf_del_bak` VALUES (3, 'admin_role', '{\"id\":1,\"admin_id\":16,\"role_id\":1,\"created_at\":\"2021-01-30 13:03:20\",\"updated_at\":\"2021-01-30 13:03:20\"}', '2021-01-30 13:03:26');
INSERT INTO `txf_del_bak` VALUES (4, 'role', '{\"id\":2,\"role_name\":\"tes\",\"description\":\"1\",\"status\":1,\"created_at\":\"2021-01-30 13:03:43\",\"updated_at\":\"2021-01-30 13:03:46\"}', '2021-01-30 13:03:53');
INSERT INTO `txf_del_bak` VALUES (5, 'role_permission', '{\"id\":10,\"role_id\":2,\"permission_id\":19,\"created_at\":\"2021-01-30 13:03:51\",\"updated_at\":\"2021-01-30 13:03:51\"}', '2021-01-30 13:03:53');
INSERT INTO `txf_del_bak` VALUES (6, 'role_permission', '{\"id\":11,\"role_id\":2,\"permission_id\":20,\"created_at\":\"2021-01-30 13:03:51\",\"updated_at\":\"2021-01-30 13:03:51\"}', '2021-01-30 13:03:53');
INSERT INTO `txf_del_bak` VALUES (7, 'role_permission', '{\"id\":12,\"role_id\":2,\"permission_id\":21,\"created_at\":\"2021-01-30 13:03:51\",\"updated_at\":\"2021-01-30 13:03:51\"}', '2021-01-30 13:03:53');
INSERT INTO `txf_del_bak` VALUES (8, 'role', '{\"id\":3,\"role_name\":\"1\",\"description\":\"1\",\"status\":1,\"created_at\":\"2021-01-30 13:03:57\",\"updated_at\":\"2021-01-30 13:03:57\"}', '2021-01-30 13:04:00');
INSERT INTO `txf_del_bak` VALUES (9, 'permission', '{\"id\":161,\"pid\":0,\"type\":1,\"icon\":\"\",\"title\":\"1test\",\"index\":\"\",\"uri\":\"\",\"status\":1,\"sort\":255,\"created_at\":\"2021-01-30 13:08:21\",\"updated_at\":\"2021-01-30 13:08:31\"}', '2021-01-30 13:08:36');
INSERT INTO `txf_del_bak` VALUES (10, 'admin', '{\"id\":17,\"name\":\"test\",\"mobile\":\"13555555555\",\"password\":\"ea9be55932da1578d949e2150942cfc9\",\"salt\":\"293b78b3f792a36b11e3716d8536caf3\",\"status\":1,\"last_login_time\":null,\"login_error_times\":0,\"created_at\":\"2021-01-30 13:21:16\",\"updated_at\":\"2021-01-30 13:21:16\"}', '2021-01-30 13:21:21');
INSERT INTO `txf_del_bak` VALUES (11, 'role_permission', '{\"id\":1,\"role_id\":1,\"permission_id\":19,\"created_at\":\"2021-01-29 21:45:40\",\"updated_at\":\"2021-01-29 21:45:40\"}', '2021-01-30 13:29:27');
INSERT INTO `txf_del_bak` VALUES (12, 'role_permission', '{\"id\":2,\"role_id\":1,\"permission_id\":20,\"created_at\":\"2021-01-29 21:45:40\",\"updated_at\":\"2021-01-29 21:45:40\"}', '2021-01-30 13:29:27');
INSERT INTO `txf_del_bak` VALUES (13, 'role_permission', '{\"id\":3,\"role_id\":1,\"permission_id\":1,\"created_at\":\"2021-01-29 21:45:40\",\"updated_at\":\"2021-01-29 21:45:40\"}', '2021-01-30 13:29:27');
INSERT INTO `txf_del_bak` VALUES (14, 'role_permission', '{\"id\":4,\"role_id\":1,\"permission_id\":3,\"created_at\":\"2021-01-29 21:45:40\",\"updated_at\":\"2021-01-29 21:45:40\"}', '2021-01-30 13:29:27');
INSERT INTO `txf_del_bak` VALUES (15, 'role_permission', '{\"id\":5,\"role_id\":1,\"permission_id\":12,\"created_at\":\"2021-01-29 21:45:40\",\"updated_at\":\"2021-01-29 21:45:40\"}', '2021-01-30 13:29:27');
INSERT INTO `txf_del_bak` VALUES (16, 'role_permission', '{\"id\":6,\"role_id\":1,\"permission_id\":4,\"created_at\":\"2021-01-29 21:45:40\",\"updated_at\":\"2021-01-29 21:45:40\"}', '2021-01-30 13:29:27');
INSERT INTO `txf_del_bak` VALUES (17, 'role_permission', '{\"id\":7,\"role_id\":1,\"permission_id\":17,\"created_at\":\"2021-01-29 21:45:40\",\"updated_at\":\"2021-01-29 21:45:40\"}', '2021-01-30 13:29:27');
INSERT INTO `txf_del_bak` VALUES (18, 'role_permission', '{\"id\":8,\"role_id\":1,\"permission_id\":2,\"created_at\":\"2021-01-29 21:45:40\",\"updated_at\":\"2021-01-29 21:45:40\"}', '2021-01-30 13:29:27');
INSERT INTO `txf_del_bak` VALUES (19, 'role_permission', '{\"id\":9,\"role_id\":1,\"permission_id\":8,\"created_at\":\"2021-01-29 21:45:40\",\"updated_at\":\"2021-01-29 21:45:40\"}', '2021-01-30 13:29:27');
INSERT INTO `txf_del_bak` VALUES (20, 'role_permission', '{\"id\":13,\"role_id\":1,\"permission_id\":19,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (21, 'role_permission', '{\"id\":14,\"role_id\":1,\"permission_id\":20,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (22, 'role_permission', '{\"id\":15,\"role_id\":1,\"permission_id\":1,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (23, 'role_permission', '{\"id\":16,\"role_id\":1,\"permission_id\":3,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (24, 'role_permission', '{\"id\":17,\"role_id\":1,\"permission_id\":12,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (25, 'role_permission', '{\"id\":18,\"role_id\":1,\"permission_id\":162,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (26, 'role_permission', '{\"id\":19,\"role_id\":1,\"permission_id\":4,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (27, 'role_permission', '{\"id\":20,\"role_id\":1,\"permission_id\":17,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (28, 'role_permission', '{\"id\":21,\"role_id\":1,\"permission_id\":163,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (29, 'role_permission', '{\"id\":22,\"role_id\":1,\"permission_id\":2,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (30, 'role_permission', '{\"id\":23,\"role_id\":1,\"permission_id\":8,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (31, 'role_permission', '{\"id\":24,\"role_id\":1,\"permission_id\":164,\"created_at\":\"2021-01-30 13:29:27\",\"updated_at\":\"2021-01-30 13:29:27\"}', '2021-01-30 13:42:16');
INSERT INTO `txf_del_bak` VALUES (32, 'admin_role', '{\"id\":2,\"admin_id\":14,\"role_id\":1,\"created_at\":\"2021-01-30 13:27:23\",\"updated_at\":\"2021-01-30 13:27:23\"}', '2021-01-30 13:42:27');
INSERT INTO `txf_del_bak` VALUES (33, 'permission', '{\"id\":162,\"pid\":3,\"type\":4,\"icon\":\"\",\"title\":\"\\u5458\\u5de5\\u5217\\u8868\",\"index\":\"\",\"uri\":\"\",\"status\":1,\"sort\":255,\"created_at\":\"2021-01-30 13:28:23\",\"updated_at\":\"2021-01-30 13:43:19\"}', '2021-01-30 13:43:58');
INSERT INTO `txf_del_bak` VALUES (34, 'permission', '{\"id\":163,\"pid\":4,\"type\":4,\"icon\":\"\",\"title\":\"\\u89d2\\u8272\\u5217\\u8868\",\"index\":\"\",\"uri\":\"\\/admin\\/role\\/lists\",\"status\":1,\"sort\":255,\"created_at\":\"2021-01-30 13:28:59\",\"updated_at\":\"2021-01-30 13:28:59\"}', '2021-01-30 13:44:19');
INSERT INTO `txf_del_bak` VALUES (35, 'permission', '{\"id\":164,\"pid\":2,\"type\":4,\"icon\":\"\",\"title\":\"\\u6743\\u9650\\u5217\\u8868\",\"index\":\"\",\"uri\":\"\\/admin\\/permission\\/lists\",\"status\":1,\"sort\":255,\"created_at\":\"2021-01-30 13:29:17\",\"updated_at\":\"2021-01-30 13:29:17\"}', '2021-01-30 13:44:36');
INSERT INTO `txf_del_bak` VALUES (36, 'role_permission', '{\"id\":25,\"role_id\":1,\"permission_id\":1,\"created_at\":\"2021-01-30 13:42:16\",\"updated_at\":\"2021-01-30 13:42:16\"}', '2021-01-30 13:44:51');
INSERT INTO `txf_del_bak` VALUES (37, 'role_permission', '{\"id\":26,\"role_id\":1,\"permission_id\":3,\"created_at\":\"2021-01-30 13:42:16\",\"updated_at\":\"2021-01-30 13:42:16\"}', '2021-01-30 13:44:51');
INSERT INTO `txf_del_bak` VALUES (38, 'role_permission', '{\"id\":27,\"role_id\":1,\"permission_id\":12,\"created_at\":\"2021-01-30 13:42:16\",\"updated_at\":\"2021-01-30 13:42:16\"}', '2021-01-30 13:44:51');
COMMIT;

-- ----------------------------
-- Table structure for txf_permission
-- ----------------------------
DROP TABLE IF EXISTS `txf_permission`;
CREATE TABLE `txf_permission` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pid` int unsigned NOT NULL COMMENT '父ID',
  `type` tinyint NOT NULL DEFAULT '1' COMMENT '1:一级菜单 2:二级菜单 3:三级及以上菜单 4:操作(不显示在菜单栏)',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单ICON',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '名称',
  `index` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '前端路由',
  `uri` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '对应请求路径',
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '状态',
  `sort` int NOT NULL DEFAULT '255' COMMENT '排序',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE,
  KEY `sort` (`sort`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='后台权限表';

-- ----------------------------
-- Records of txf_permission
-- ----------------------------
BEGIN;
INSERT INTO `txf_permission` VALUES (1, 0, 1, 'el-icon-s-tools', '系统设置', '', '', 1, 0, '2020-02-25 16:40:44', '2020-10-09 12:34:11');
INSERT INTO `txf_permission` VALUES (2, 1, 2, '', '权限管理', 'permission', '/admin/permission/lists', 1, 0, '2020-02-25 16:40:44', '2021-01-30 13:44:31');
INSERT INTO `txf_permission` VALUES (3, 1, 2, '', '员工管理', 'staff', '/admin/admin/lists', 1, 2, '2020-02-25 16:40:44', '2021-01-30 13:43:27');
INSERT INTO `txf_permission` VALUES (4, 1, 2, '', '角色管理', 'role', '/admin/role/lists', 1, 1, '2020-02-25 16:40:44', '2021-01-30 13:44:14');
INSERT INTO `txf_permission` VALUES (5, 2, 4, '', '删除权限', '', '/admin/permission/batchDelete', 1, 255, '2020-02-27 15:28:20', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (6, 2, 4, '', '修改权限', '', '/admin/permission/update', 1, 256, '2020-02-27 15:29:04', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (7, 2, 4, '', '新增权限', '', '/admin/permission/insert', 1, 255, '2020-02-27 15:31:25', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (8, 2, 4, '', '权限下拉数据', '', '/admin/permission/getPermissionDropDown', 1, 255, '2020-02-27 15:40:04', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (9, 3, 4, '', '删除员工', '', '/admin/admin/batchDelete', 1, 255, '2020-02-27 15:42:13', '2020-10-09 13:07:33');
INSERT INTO `txf_permission` VALUES (10, 3, 4, '', '修改员工', '', '/admin/admin/update', 1, 256, '2020-02-27 15:43:06', '2020-10-09 13:07:33');
INSERT INTO `txf_permission` VALUES (11, 3, 4, '', '新增员工', '', '/admin/admin/insert', 1, 257, '2020-02-27 15:43:37', '2020-10-09 13:07:33');
INSERT INTO `txf_permission` VALUES (12, 3, 4, '', '绑定角色', '', '/admin/role/getRolesWithAdmin', 1, 255, '2020-02-27 15:50:33', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (13, 3, 4, '', '绑定角色保存', '', '/admin/admin/saveRoles', 1, 255, '2020-02-27 15:52:10', '2020-10-09 13:07:33');
INSERT INTO `txf_permission` VALUES (14, 4, 4, '', '删除角色', '', '/admin/role/batchDelete', 1, 255, '2020-02-27 15:53:33', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (15, 4, 4, '', '修改角色', '', '/admin/role/update', 1, 256, '2020-02-27 15:53:49', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (16, 4, 4, '', '新增角色', '', '/admin/role/insert', 1, 255, '2020-02-27 15:54:12', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (17, 4, 4, '', '绑定权限', '', '/admin/permission/getPermissionsWithRole', 1, 255, '2020-02-27 15:55:12', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (18, 4, 4, '', '保存绑定权限', '', '/admin/role/savePermissions', 1, 255, '2020-02-27 15:55:40', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (19, 0, 1, 'el-icon-s-home', '系统首页', 'dashboard', '/admin/index/index', 1, 9999, '2020-02-27 15:03:24', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (20, 19, 4, '', '退出登录', '', '/admin/public/loginOut', 1, 255, '2020-02-27 16:00:10', '2020-10-09 13:06:52');
INSERT INTO `txf_permission` VALUES (21, 19, 4, '', '更改密码', '', '/admin/admin/updatePassword', 1, 255, '2020-02-27 16:00:48', '2020-10-09 13:07:33');
COMMIT;

-- ----------------------------
-- Table structure for txf_role
-- ----------------------------
DROP TABLE IF EXISTS `txf_role`;
CREATE TABLE `txf_role` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '角色名称',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色描述',
  `status` tinyint NOT NULL COMMENT '角色状态',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='后台角色表';

-- ----------------------------
-- Records of txf_role
-- ----------------------------
BEGIN;
INSERT INTO `txf_role` VALUES (1, '访客', '访客', 1, '2021-01-29 21:42:59', '2021-01-29 21:42:59');
COMMIT;

-- ----------------------------
-- Table structure for txf_role_permission
-- ----------------------------
DROP TABLE IF EXISTS `txf_role_permission`;
CREATE TABLE `txf_role_permission` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int unsigned NOT NULL COMMENT '角色ID',
  `permission_id` int unsigned NOT NULL COMMENT '权限ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `role_id` (`role_id`) USING BTREE,
  KEY `permission_id` (`permission_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='后台 角色、权限关联表';

-- ----------------------------
-- Records of txf_role_permission
-- ----------------------------
BEGIN;
INSERT INTO `txf_role_permission` VALUES (28, 1, 19, '2021-01-30 13:44:51', '2021-01-30 13:44:51');
INSERT INTO `txf_role_permission` VALUES (29, 1, 20, '2021-01-30 13:44:51', '2021-01-30 13:44:51');
INSERT INTO `txf_role_permission` VALUES (30, 1, 1, '2021-01-30 13:44:51', '2021-01-30 13:44:51');
INSERT INTO `txf_role_permission` VALUES (31, 1, 3, '2021-01-30 13:44:51', '2021-01-30 13:44:51');
INSERT INTO `txf_role_permission` VALUES (32, 1, 12, '2021-01-30 13:44:51', '2021-01-30 13:44:51');
INSERT INTO `txf_role_permission` VALUES (33, 1, 4, '2021-01-30 13:44:51', '2021-01-30 13:44:51');
INSERT INTO `txf_role_permission` VALUES (34, 1, 17, '2021-01-30 13:44:51', '2021-01-30 13:44:51');
INSERT INTO `txf_role_permission` VALUES (35, 1, 2, '2021-01-30 13:44:51', '2021-01-30 13:44:51');
INSERT INTO `txf_role_permission` VALUES (36, 1, 8, '2021-01-30 13:44:51', '2021-01-30 13:44:51');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
