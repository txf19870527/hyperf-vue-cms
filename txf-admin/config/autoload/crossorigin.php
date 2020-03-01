<?php

declare(strict_types=1);

return [
    'allow_domain_list' => [
        "http://127.0.0.1:8080",
        "http://localhost:8080",
        "https://admin.fengfengphp.com",
    ],
    'allow_request_type' => [
        'POST'
    ],
    'allow_headers' => [
        'x-requested-with', 'DNT', 'Keep-Alive', 'User-Agent', 'Cache-Control', 'Content-Type', 'Authorization', 'Origin', 'X-Requested-With', 'Accept', 'Referer'
    ],
    'allow_credentials' => 'true',
];
