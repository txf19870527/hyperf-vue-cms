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
return [
    'default' => [
        'driver' => Hyperf\AsyncQueue\Driver\RedisDriver::class,
        'redis' => [
            'pool' => 'queue'
        ],
        'channel' => 'queue',
        'timeout' => 3,
        'retry_seconds' => 10,
        'handle_timeout' => 30,
        'processes' => 1,
        'concurrent' => [
            'limit' => 1
        ]
    ],
];
