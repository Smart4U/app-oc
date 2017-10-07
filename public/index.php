<?php declare(strict_types=1);

/**
 * Front controller
 *
 * PHP version 7.1
 */


/**
 * Bootstrap file to initializes application dependencies
 */
require(dirname(__DIR__) . '/bootstrap.php');



$app = new \App\Core\App();

echo $app->run();
