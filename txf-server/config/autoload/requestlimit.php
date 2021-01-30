<?php

declare(strict_types=1);

/**
 * 频繁点击限制
 */
$config = [
    '/admin/public/login' => [
        'time' => 3,                // N秒内禁止频繁点击，默认值：3
        'use_token' => true,        // 使用token来细化限制的key。注意：有些接口不需要token（即：效果等同于该参数设置为false）。默认值：true
        'body_keys' => ['mobile', 'password'] // 加入某些请求参数来细化限制的key。默认：[]
    ],
    '/admin/admin/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['mobile']
    ],
    '/admin/permission/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['pid', 'type', 'title']
    ],
    '/admin/role/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['role_name']
    ],
    '/admin/hospital/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['hospital_name']
    ],
    '/admin/hospital/import' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => []
    ],
    '/admin/product/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['product_name']
    ],
    '/admin/section/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['section_name']
    ],
    '/admin/productSection/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['product_name', 'section_id']
    ],
    '/admin/doctor/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['doctor_name']
    ],
    '/admin/doctor/audit' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['ids']
    ],
    '/admin/feedback/reply' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['top_id']
    ],
    '/admin/manager/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['phone']
    ],
    '/admin/region/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['product_id']
    ],
    '/admin/hospitalProduct/import' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => []
    ],
    '/admin/hospitalProduct/importSubmit' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => []
    ],
    '/admin/contract/download' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['id']
    ],
    '/admin/visitGoal/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['product_name']
    ],
    '/admin/productTopic/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['product_name']
    ],
    '/admin/material/insert' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['title']
    ],
    '/admin/leads/import' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => []
    ],
    '/admin/leads/importSubmit' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => []
    ],
    '/admin/recommend/import' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => []
    ],
    '/admin/recommend/importSubmit' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => []
    ],
    '/admin/userOption/import' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => []
    ],
    '/admin/fish/register' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['user_id']
    ],
    '/admin/fish/postDemand' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['id']
    ],
    '/admin/fish/import' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => []
    ],
    '/admin/fish/importSubmit' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['reqId']
    ],
    '/admin/fish/resetPayUrl' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['reqId']
    ],
    '/admin/fish/createOrder' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['reqId']
    ],
    '/admin/fish/orderAudit' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['orderId']
    ],
    '/admin/role/savePermissions' => [
        'time' => 3,
        'use_token' => true,
        'body_keys' => ['id']
    ],
];

$versions = ["v2", "v3"];

/* api相关*/
foreach ($versions as $version) {
    // todo
    $config["/api/{$version}/public/login"] = [
        'body_keys' => ['phone']
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