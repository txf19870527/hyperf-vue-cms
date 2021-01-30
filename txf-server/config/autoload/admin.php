<?php

declare(strict_types=1);

/**
 * admin系统对应的配置
 */
return [
    'no_check_uri' => [     // 不需要授权就能访问的页面
        '/admin/public/login', '/admin/public/loginOut', '/admin/public/test', '/admin/public/downloadExample', '/admin/index/index'
    ],

    'must_login_uri' => [   // 有些页面可能不需要权限，但必须是登录状态才能访问
        '/admin/public/loginOut', '/admin/index/index'
    ],
    'session_time_out' => 3600 * 24 * 7,
    'login_error_times' => 10,
    'super_admin_id' => [
        1
    ],
    'super_permission_id' => [
        1, 2, 3, 4
    ],
];

