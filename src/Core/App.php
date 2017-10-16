<?php

namespace App\Core;

use Psr\Container\ContainerInterface;

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
     * @return string
     */
    public function run(): string
    {
        return 'app loaded...';
    }
}
