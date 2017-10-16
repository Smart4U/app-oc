<?php declare(strict_types=1);

/**
 * Front controller
 *
 * PHP version 7.1
 */
use App\Core\App;
use GuzzleHttp\Psr7\ServerRequest;
use function Http\Response\send;

/**
 * Bootstrap file to initializes application dependencies
 */
$container = require_once dirname(__DIR__) . '/bootstrap.php';

$app = new App($container);

if (php_sapi_name() !== 'cli') {
    send($app->run(
        ServerRequest::fromGlobals()
    ));
}
