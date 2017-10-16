<?php

return [

    'default' => 'production',

    'production' => [

        'driver'   => 'mysql',
        'charset'  => 'utf8',

        'port'     => getenv('DB_PORT'),
        'hostname' => getenv('DB_HOST'),
        'database' => getenv('DB_NAME'),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASS')

    ],

    'tests' => [

        'driver'  => 'sqlite',
        'name'    => ':memory:'

    ]

];