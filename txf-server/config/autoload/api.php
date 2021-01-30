<?php

declare(strict_types=1);

/**
 * api对应的访问权限配置
 */
$config = [
    'no_login_uri' => [],                   // 不需要登录就能访问的接口
    'session_time_out' => 3600 * 24 * 7,
    'login_error_times' => 20,


];

$versions = [
    'v2', 'v3'
];

// v2、v3直接复制2份
foreach ($versions as $version) {

    // 不需要登录
    $config['no_login_uri'] = array_merge($config['no_login_uri'], [
        "/api/{$version}/user/test",
    ]);

}

return $config;

