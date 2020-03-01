<?php

declare(strict_types=1);

use Monolog\Formatter;
use Monolog\Logger;

return [
    'default' => [
        'handlers' => [
            [
                'class' => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/info/info.log',
                    'level' => Logger::INFO,
                ],
                'formatter' => [
                    'class' => Formatter\JsonFormatter::class,
                    'constructor' => [
                        'batchMode' => Formatter\JsonFormatter::BATCH_MODE_JSON,
                        'appendNewline' => true,
                    ],
                ],
            ],
            [
                'class' => Monolog\Handler\RotatingFileHandler::class,
                'constructor' => [
                    'filename' => BASE_PATH . '/runtime/logs/error/error.log',
                    'level' => Logger::ERROR
                ],
                'formatter' => [
                    'class' => Formatter\JsonFormatter::class,
                    'constructor' => [
                        'batchMode' => Formatter\JsonFormatter::BATCH_MODE_JSON,
                        'appendNewline' => true,
                    ],
                ],
            ],
        ],
    ],
];

