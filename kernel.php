<?php

/**
 * The kernel that contains the dependencies of my project
 */

use App\Core\App;
use Psr\Container\ContainerInterface;
use function DI\{get,object};


return [

    // APP
    App::class => object()->constructor(ContainerInterface::class),

];