<?php

namespace App\Core;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class App
 * @package App\Core
 */
class App
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * App constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Start the application and send the response
     *
     * @return ResponseInterface
     */
    public function run(): ResponseInterface
    {
        ServerRequest::fromGlobals();

        return new Response(200, [], 'app loaded...', 1.1);
    }
}
