<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Log\LogLevel;

return [
    'app_name' => env('APP_NAME', 'skeleton'),
    'app_env' => env('APP_ENV', 'dev'),
    'scan_cacheable' => env('SCAN_CACHEABLE', false),
    StdoutLoggerInterface::class => [
        'log_level' => [
            LogLevel::ALERT,
            LogLevel::CRITICAL,
//            LogLevel::DEBUG,
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
            LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING,
        ],
    ],
    'request_log' => true,              // 记录请求日志开关，同时依赖 log_info 的配置
    'request_log_response' => false,    // 是否记录响应日志，true：全记录 false：过滤掉uri中带lists的响应值 或者响应值长度超过2000的
    'request_error_log' => true,        // 记录请求错误日志开关，同时依赖 log_error 的配置
    'log_info' => true,                 // info日志开关
    'log_warning' => true,              // warning日志开关
    'log_error' => true,                // error日志开关
];
