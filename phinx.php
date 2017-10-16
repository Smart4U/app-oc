<?php

require __DIR__ . '/public/index.php';

return [

    'paths' => [
        'migrations' => 'database/migrations',
        'seeds'      => 'database/seeds'
    ],

    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',

        'development' => [
            'adapter'    => 'mysql',
            'host'       => getenv('DB_HOST'),
            'name'       => getenv('DB_NAME'),
            'user'       => getenv('DB_USER'),
            'pass'       => getenv('DB_PASS'),
            'port'       => getenv('DB_PORT')
        ]



    ]

];