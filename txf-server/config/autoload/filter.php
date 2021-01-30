<?php

declare(strict_types=1);

$versions = [
    "v2", "v3"
];

/**
 * 黑名单权重高于白名单
 */
$config = [
    /******************************** 管理系统权限配置 ****************************************/
    '/admin/public/login' => [
        'white' => ['mobile', 'password'],
        'black' => []
    ],
    '/admin/admin/batchDelete' => [
        'white' => ['ids']
    ],
    '/admin/admin/update' => [
        'white' => ['id', 'name', 'mobile', 'status', 'login_error_times']
    ],
    '/admin/admin/insert' => [
        'white' => ['name', 'mobile', 'password', 'status']
    ],
    '/admin/admin/saveRoles' => [
        'white' => ['id', 'roles']
    ],
    '/admin/admin/updatePassword' => [
        'white' => ['old_password', 'new_password', 'again_password']
    ],
    '/admin/role/batchDelete' => [
        'white' => ['ids']
    ],
    '/admin/role/update' => [
        'white' => ['id', 'role_name', 'description', 'status']
    ],
    '/admin/role/insert' => [
        'white' => ['role_name', 'description', 'status']
    ],
    '/admin/role/getRolesWithAdmin' => [
        'white' => ['admin_id']
    ],
    '/admin/role/savePermissions' => [
        'white' => ['id', 'permissions'],
    ],
    '/admin/permission/batchDelete' => [
        'white' => ['ids']
    ],
    '/admin/permission/update' => [
        'white' => ['id', 'title', 'icon', 'index', 'uri', 'status', 'sort']
    ],
    '/admin/permission/insert' => [
        'white' => ['pid', 'type', 'title', 'icon', 'index', 'uri', 'status', 'sort']
    ],
    '/admin/permission/getPermissionDropDown' => [
        'white' => ['depth', 'status']
    ],
    '/admin/permission/getPermissionsWithRole' => [
        'white' => ["role_id"]
    ],
];

/******************************** api权限配置 ****************************************/
foreach ($versions as $version) {
    $config["/api/{$version}/user/test"] = [
        "white" => []
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