<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

# 首页
Router::addRoute(["POST"], '/index/index', 'App\Controller\IndexController@index');

# 用户
Router::addRoute(["POST"], '/admin/lists', 'App\Controller\AdminController@lists');
Router::addRoute(["POST"], '/admin/batchDelete', 'App\Controller\AdminController@batchDelete');
Router::addRoute(["POST"], '/admin/update', 'App\Controller\AdminController@update');
Router::addRoute(["POST"], '/admin/insert', 'App\Controller\AdminController@insert');
Router::addRoute(["POST"], '/admin/saveRoles', 'App\Controller\AdminController@saveRoles');
Router::addRoute(["POST"], '/admin/updatePassword', 'App\Controller\AdminController@updatePassword');

# 权限
Router::addRoute(["POST"], '/permission/lists', 'App\Controller\PermissionController@lists');
Router::addRoute(["POST"], '/permission/batchDelete', 'App\Controller\PermissionController@batchDelete');
Router::addRoute(["POST"], '/permission/update', 'App\Controller\PermissionController@update');
Router::addRoute(["POST"], '/permission/insert', 'App\Controller\PermissionController@insert');
Router::addRoute(["POST"], '/permission/getPermissionCascader', 'App\Controller\PermissionController@getPermissionCascader');
Router::addRoute(["POST"], '/permission/getPermissionsWithRole', 'App\Controller\PermissionController@getPermissionsWithRole');


# 登录、登出
Router::addRoute(["POST"], '/public/login', 'App\Controller\PublicController@login');
Router::addRoute(["POST"], '/public/loginOut', 'App\Controller\PublicController@loginOut');

# 下载示例、上传示例
Router::addRoute(["POST"], '/public/downloadExample', 'App\Controller\PublicController@downloadExample');
Router::addRoute(["POST"], '/public/uploadExample', 'App\Controller\PublicController@uploadExample');

# 角色
Router::addRoute(["POST"], '/role/lists', 'App\Controller\RoleController@lists');
Router::addRoute(["POST"], '/role/batchDelete', 'App\Controller\RoleController@batchDelete');
Router::addRoute(["POST"], '/role/update', 'App\Controller\RoleController@update');
Router::addRoute(["POST"], '/role/insert', 'App\Controller\RoleController@insert');
Router::addRoute(["POST"], '/role/getRolesWithAdmin', 'App\Controller\RoleController@getRolesWithAdmin');
Router::addRoute(["POST"], '/role/savePermissions', 'App\Controller\RoleController@savePermissions');

# 上传图片
Router::addRoute(["POST"], '/public/upload', 'App\Controller\PublicController@upload');

# 回调测试
Router::addRoute(["GET"], '/callback/test', 'App\Controller\CallBack@test');



