<?php

declare(strict_types=1);

/**
 * 频繁点击限制
 */
$config = [
    '/public/login' => [
        'time' => 3,                // N秒内禁止频繁点击，默认值：3
        'use_token' => true,        // 使用token来细化限制的key。注意：有些接口不需要token（即：效果等同于该参数设置为false）。默认值：true
        'body_keys' => ['mobile','password'] // 加入某些请求参数来细化限制的key。默认：[]
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