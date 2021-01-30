<?php

declare(strict_types=1);

return [
    'allow_request_type' => [
        'OPTIONS', 'POST', 'GET'
    ],
    'allow_headers' => [
        'x-requested-with', 'DNT', 'Keep-Alive', 'User-Agent', 'Cache-Control', 'Content-Type',
        'Authorization', 'Origin', 'X-Requested-With', 'Accept', 'Referer', 'Depth', 'X-File-Size', 'X-Requested-By',
        'If-Modified-Since', 'X-File-Name', 'X-File-Type', 'token'
    ],

    'allow_credentials' => 'true',
];

