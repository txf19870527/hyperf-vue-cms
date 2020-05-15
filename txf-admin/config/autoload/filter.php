<?php

declare(strict_types=1);

/**
 * 黑名单权重高于白名单
 */
$config = [
    '/public/login' => [
        'white' => ['mobile', 'password'],
        'black' => []
    ],
    '/admin/batchDelete' => [
        'white' => ['ids']
    ],
    '/admin/update' => [
        'white' => ['id','name','mobile','status','login_error_times']
    ],
    '/admin/insert' => [
        'white' => ['name','mobile','password','status']
    ],
    '/admin/saveRoles' => [
        'white' => ['id','roles']
    ],
    '/admin/updatePassword' => [
        'white' => ['old_password','new_password','again_password']
    ],
    '/role/batchDelete' => [
        'white' => ['ids']
    ],
    '/role/update' => [
        'white' => ['id','role_name','description','status']
    ],
    '/role/insert' => [
        'white' => ['role_name','description','status']
    ],
    '/role/getRolesWithAdmin' => [
        'white' => ['admin_id']
    ],
    '/role/savePermissions' => [
        'white' => ['id', 'permissions'],
    ],
    '/permission/batchDelete' => [
        'white' => ['ids']
    ],
    '/permission/update' => [
        'white' => ['id','title','icon','index','uri','status','sort']
    ],
    '/permission/insert' => [
        'white' => ['pid','type','title','icon','index','uri','status','sort']
    ],
    '/permission/getPermissionCascader' => [
        'white' => ['depth', 'status']
    ],
    '/permission/getPermissionsWithRole' => [
        'white' => ["role_id"]
    ],
];

if (empty($config)) {
    return $config;
}

$newConfig = [];

foreach ($config as $key => $value) {
    $newConfig[strtolower($key)] = $value;
}

unset($config);

return $newConfig;