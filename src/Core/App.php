<?php

namespace App\Core;

use App\Core\Renderer\RendererInterface;
use App\Core\Routing\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
        if ($this->container->has('bundles')) {
            foreach ($this->container->get('bundles') as $bundle) {
                $this->container->get($bundle);
            }
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this->container->get(Router::class)->dispatch(
            $request, $this->container, $this->container->get(RendererInterface::class)
        );

    }
}
