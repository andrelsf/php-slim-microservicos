<?php

define('APP_ROOT', __DIR__);

return [
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => false,
        'doctrine' => [
            // if true, metadata caching is forcefully disabled
            'dev_mode' => true,
            // path where the compiled metadata info will be cached
            // make sure the path exists and it is writable
            'cache_dir' => APP_ROOT . '/data/cache/doctrine',
            // you should add any other path containing annotated entity classes
            'metadata_dirs' => [APP_ROOT . '/src/Models/Entity'],
            'connection' => [
                'driver' => 'pdo_mysql',
                'host' => '172.17.0.2',
                'port' => 3306,
                'dbname' => 'users',
                'user' => 'dbuser',
                'password' => 'dbuser123',
            ]
        ]
    ]
];