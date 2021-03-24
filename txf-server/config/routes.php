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

// 后台路由分组
Router::addGroup(
    '/admin', function () {

    # 登录、登出
    Router::addRoute(["POST"], '/public/login', 'App\Controller\Admin\PublicController@login');
    Router::addRoute(["POST"], '/public/loginOut', 'App\Controller\Admin\PublicController@loginOut');

    # 测试接口
    Router::addRoute(["POST", "GET"], '/public/test', 'App\Controller\Admin\PublicController@test');
    Router::addRoute(["POST"], '/public/downloadExample', 'App\Controller\Admin\PublicController@downloadExample');
    Router::addRoute(["POST"], '/public/uploadExample', 'App\Controller\Admin\PublicController@uploadExample');

    # 首页
    Router::addRoute(["POST"], '/index/index', 'App\Controller\Admin\IndexController@index');

    # 后台用户
    Router::addRoute(["POST"], '/admin/lists', 'App\Controller\Admin\System\AdminController@lists');
    Router::addRoute(["POST"], '/admin/batchDelete', 'App\Controller\Admin\System\AdminController@batchDelete');
    Router::addRoute(["POST"], '/admin/update', 'App\Controller\Admin\System\AdminController@update');
    Router::addRoute(["POST"], '/admin/insert', 'App\Controller\Admin\System\AdminController@insert');
    Router::addRoute(["POST"], '/admin/saveRoles', 'App\Controller\Admin\System\AdminController@saveRoles');
    Router::addRoute(["POST"], '/admin/updatePassword', 'App\Controller\Admin\System\AdminController@updatePassword');

    # 权限
    Router::addRoute(["POST"], '/permission/lists', 'App\Controller\Admin\System\PermissionController@lists');
    Router::addRoute(["POST"], '/permission/batchDelete', 'App\Controller\Admin\System\PermissionController@batchDelete');
    Router::addRoute(["POST"], '/permission/update', 'App\Controller\Admin\System\PermissionController@update');
    Router::addRoute(["POST"], '/permission/insert', 'App\Controller\Admin\System\PermissionController@insert');
    Router::addRoute(["POST"], '/permission/getPermissionDropDown', 'App\Controller\Admin\System\PermissionController@getPermissionDropDown');
    Router::addRoute(["POST"], '/permission/getPermissionsWithRole', 'App\Controller\Admin\System\PermissionController@getPermissionsWithRole');

    # 角色
    Router::addRoute(["POST"], '/role/lists', 'App\Controller\Admin\System\RoleController@lists');
    Router::addRoute(["POST"], '/role/batchDelete', 'App\Controller\Admin\System\RoleController@batchDelete');
    Router::addRoute(["POST"], '/role/update', 'App\Controller\Admin\System\RoleController@update');
    Router::addRoute(["POST"], '/role/insert', 'App\Controller\Admin\System\RoleController@insert');
    Router::addRoute(["POST"], '/role/getRolesWithAdmin', 'App\Controller\Admin\System\RoleController@getRolesWithAdmin');
    Router::addRoute(["POST"], '/role/savePermissions', 'App\Controller\Admin\System\RoleController@savePermissions');


},
    [
        'middleware' => [
            \App\Middleware\InitMiddleware::class,
            \App\Middleware\CrossOriginMiddleware::class,
            \App\Middleware\Admin\AuthMiddleware::class,
            \App\Middleware\FilterMiddleware::class,
            \App\Middleware\ValidationMiddleware::class,
            \App\Middleware\RequestLimitMiddleware::class,
            \App\Middleware\UploadMiddleware::class
        ]
    ]
);

// V2、V3公用路由，（如需配置版本单独路由，单独搞一个数组后，在下方单独设置）
$routes = [
    ['request_method' => ["POST"], 'route' => '/user/test', 'class' => 'App\Controller\Api\{{version}}\UserController', 'method' => 'test'],
];

// beta版，正在思考改进中
// v2、v3是互切版本，不是降级版本。目的是为了兼容APP发布审核那段时间，不做多版本兼容，发布大版本后会提示强制更新。
// v2切v3的时候，把最新的不能兼容的逻辑写v3中。v3切v2的时候，需要把v3的部分或全部代码先覆盖到v2中并清理掉兼容无用代码，然后再在v2中写最新不兼容逻辑，v3相应做一些兼容处理
// 需要清理的垃圾代码一般在（controller service validation filter 以及service有调用的model或queue）中
// 第2行比较绕，等想到好的方案再优化一下
$versions = [
    ['current_version_key' => 'v2', 'current_version' => 'V2', 'other_version' => 'V3'],
    ['current_version_key' => 'v3', 'current_version' => 'V3', 'other_version' => 'V2'],
];

foreach ($versions as $version) {
    // 前台路由分组
    Router::addGroup(
        "/api/{$version['current_version_key']}", function () use ($version, $routes) {

        foreach ($routes as $v) {
            $current = str_replace("{{version}}", $version['current_version'], $v['class']);
            $other = str_replace("{{version}}", $version['other_version'], $v['class']);

            if (method_exists($current, $v['method'])) {
                Router::addRoute($v['request_method'], $v['route'], $current . "@" . $v['method']);
            } else {
                Router::addRoute($v['request_method'], $v['route'], $other . "@" . $v['method']);
            }
        }

        // 如需配置版本单独路由，在此设置

    }, [
            'middleware' => [
                \App\Middleware\InitMiddleware::class,
                \App\Middleware\CrossOriginMiddleware::class,
                \App\Middleware\Api\AuthMiddleware::class,
                \App\Middleware\FilterMiddleware::class,
                \App\Middleware\ValidationMiddleware::class,
                \App\Middleware\RequestLimitMiddleware::class,
            ]
        ]
    );
}
