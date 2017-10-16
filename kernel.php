<?php

/**
 * The kernel that contains the dependencies of my project
 */


use function DI\{get,object};

return [

    // DATABASE
    \App\Core\Database\Database::class => object()->constructor(get('database'))

];