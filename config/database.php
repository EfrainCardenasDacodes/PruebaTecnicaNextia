<?php
    return [
        'default' => 'postgresql',
        'connections' => [
            'postgresql' => [ 
                'driver' => 'pgsql',
                'host' => env('DB_HOST', 'localhost'),
                'port' => env('DB_PORT', 27017),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
            ],
        ],
        'migrations' => 'migrations',
    ];