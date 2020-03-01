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


// 生成中间件文件
// php bin/hyperf.php gen:middleware AuthMiddleware
// 中间件有依赖，顺序不要乱调整
return [
    'http' => [
        \App\Middleware\InitMiddleware::class,              # 初始化、日志等
        \App\Middleware\CrossOriginMiddleware::class,       # 跨域
        \App\Middleware\RequestLimitMiddleware::class,      # 防止网络差的情况下出现客户端频繁点击（跟限流有一些区别，要使用限流的话直接使用hyperf的RateLimit组件）
        \App\Middleware\AuthMiddleware::class,              # 权限验证
        \App\Middleware\FilterMiddleware::class,            # 参数过滤
        \App\Middleware\ValidationMiddleware::class,        # 参数验证
        \App\Middleware\UploadMiddleware::class,            # 上传文件处理
    ],
];
