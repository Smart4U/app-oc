<?php

/**
 * The kernel that contains the dependencies of my project
 */


use function DI\{get,object};

return [

    // ROUTING
    \App\Core\Routing\Router::class => object(),
    \App\Core\Routing\Route::class => object(),

    // DATABASE
    \App\Core\Database\Database::class => object()->constructor(get('database')),

];