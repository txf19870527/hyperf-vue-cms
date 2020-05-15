<?php

declare(strict_types=1);

/**
 * admin系统对应的配置
 */
return [
    'session_time_out' => 3600 * 24,
    'login_error_times' => 5,
    'test_account' => ['13000000000'],      // 测试账号不进行 login_error_times 限制
    'super_admin_id' => [
        1
    ],
    'super_permission_id' => [
        1,2,3,4
    ],
];
