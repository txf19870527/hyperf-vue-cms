<?php

declare(strict_types=1);

$versions = [
    'v2', 'v3'
];

/**
 * 自定义接口参数验证
 * 默认的验证规则都是比较宽松的，请根据实际需要进行调整
 */
$config = [
    '/admin/public/login' => [
        'mobile' => 'required|digits:11',
        'password' => 'required|string|min:6',
    ],
    // 自定义消息，也可以在 storage/languages/zh_CN/validation.php['custom']中统一配置
//    '/admin/public/login#message#' => [
//        'size' => '手机号不合法'
//    ],
    '/admin/admin/batchDelete' => [
        'ids' => 'required|array'
    ],
    '/admin/admin/update' => [
        'id' => 'required|integer',
        'name' => 'string',
        'mobile' => 'digits:11',
        'status' => 'in:1,0',
        'login_error_times' => 'integer'
    ],
    '/admin/admin/insert' => [
        'name' => 'required|string',
        'mobile' => 'required|digits:11',
        'password' => 'required|string|min:6',
        'status' => 'required|in:1,0'
    ],
    '/admin/admin/saveRoles' => [
        'id' => 'required|integer',
        'roles' => 'array'
    ],
    '/admin/admin/updatePassword' => [
        'old_password' => 'required|string|min:6',
        'new_password' => 'required|string|min:6',
        'again_password' => 'required|same:new_password|string|min:6',
    ],
    '/admin/role/batchDelete' => [
        'ids' => 'required|array'
    ],
    '/admin/role/update' => [
        'id' => 'required|integer',
        'role_name' => 'required|string',
        'status' => 'required|in:1,0',
    ],
    '/admin/role/insert' => [
        'role_name' => 'required|string',
        'status' => 'required|in:1,0',
    ],
    '/admin/role/getRolesWithAdmin' => [
        'admin_id' => 'required|integer'
    ],
    '/admin/role/savePermissions' => [
        'id' => 'required|integer',
        'permissions' => 'array'
    ],
    '/admin/permission/batchDelete' => [
        'ids' => 'required|array'
    ],
    '/admin/permission/update' => [
        'id' => 'required|integer',
        'title' => "required|string",
        'icon' => "string",
        'index' => "string",
        "uri" => "string",
        "status" => 'required|in:1,0',
        "sort" => 'required|integer',
    ],
    '/admin/permission/insert' => [
        'pid' => 'required|integer',
        'type' => 'required|integer',
        'title' => "required|string",
        'icon' => "string",
        'index' => "string",
        "uri" => "string",
        "status" => 'required|in:1,0',
        "sort" => 'required|integer',
    ],
    '/admin/permission/getPermissionDropDown' => [
        'depth' => 'in:1,2,3',
        'status' => 'in:0,1'
    ],
    '/admin/permission/getPermissionsWithRole' => [
        'role_id' => 'required|integer'
    ],
];

/* api相关*/
foreach ($versions as $version) {
    $config["/api/{$version}/user/test"] = [
    ];
}

if (empty($config)) {
    return $config;
}

$newConfig = [];

foreach ($config as $key => $value) {
    $newConfig[strtolower($key)] = $value;
}

unset($config);

return $newConfig;