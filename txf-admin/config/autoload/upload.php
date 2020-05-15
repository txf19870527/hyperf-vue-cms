<?php

/**
 * 上传相关处理
 */
$config = [
    'upload_uri' => [   // 上传文件，永久保存
        '/public/upload'
    ],
    'import_uri' => [   // 导入文件，存放临时目录，根据需求按需清理（如上传的是EXCEL，解析入库后，不需要保存该文件）
        '/public/uploadExample'
    ],

    'upload_not_require_uri' => [ // 文件非必填uri

    ],
    'import_not_require_uri' => [ // 文件非必填uri
        
    ],
    
    // 上传相关配置
    'upload_max_size' => 2 * 1024 * 1024,        // 不要设置的太大，如果超过2M的话，需要同时调整swoole package_max_length 配置
    'upload_allow_suffix' => ['png','jpg','jpeg'],
    'upload_allow_type' => ['image/png','image/jpeg'],
    'upload_user_setting' => [  // 自定义配置 uri => ["upload_max_size"=>xxx...]

    ],
    // 导入相关配置
    'import_max_size' => 2 * 1024 * 1024,         // 不要设置的太大，如果超过2M的话，需要同时调整swoole package_max_length 配置
    'import_allow_suffix' => ['xlsx'],
    'import_allow_type' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/octet-stream'],
    'import_user_setting' => [      // 自定义配置 uri => ["import_max_size"=>xxx...]
    ]
];


if (!empty($config['upload_user_setting'])) {
    $newSitting = [];
    foreach ($config['upload_user_setting'] as $key => $value) {
        $newSitting[strtolower($key)] = $value;
    }
    $config['upload_user_setting'] = $newSitting;
    unset($newSitting);
}

if (!empty($config['import_user_setting'])) {
    $newSitting = [];
    foreach ($config['import_user_setting'] as $key => $value) {
        $newSitting[strtolower($key)] = $value;
    }
    $config['import_user_setting'] = $newSitting;
    unset($newSitting);
}


return $config;