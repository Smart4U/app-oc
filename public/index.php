<?php declare(strict_types=1);

/**
 * Front controller
 *
 * PHP version 7.1
 */



/**
 * Bootstrap file to initializes application dependencies
 */
$container = require_once dirname(__DIR__) . '/bootstrap.php';

$app = new \App\Core\App($container);

die($app->run());
