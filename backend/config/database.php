<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', '3306'),
            'database'  => env('DB_DATABASE', 'alghani_erp'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ],

        // READ-ONLY secondary connection used by LegacyImportSeeder.
        // Points at the existing production ERP DB at C:\xampp\htdocs\alghani.
        // Activated only when LEGACY_IMPORT_ENABLED=true (safety guard).
        'legacy' => [
            'driver'    => 'mysql',
            'host'      => env('LEGACY_DB_HOST', '127.0.0.1'),
            'port'      => env('LEGACY_DB_PORT', '3306'),
            'database'  => env('LEGACY_DB_DATABASE', 'alghani'),
            'username'  => env('LEGACY_DB_USERNAME', 'root'),
            'password'  => env('LEGACY_DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => false, // legacy data may have lax types
            'engine'    => null,
        ],
    ],

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],
];
