<?php

declare(strict_types=1);

/**
 * 自定义权限验证
 */
return [
    'no_check_uri' => [     // 不需要授权就能访问的页面
        '/public/login', '/public/loginOut', '/index/index', '/callback/test'
    ],
    'must_login_uri' => [   // 有些页面可能不需要权限，但必须是登录状态才能访问
        '/public/loginOut', '/index/index'
    ],
    'super_admin_id' => [
        1
    ],
    'session_time_out' => 3600 * 24,
];
