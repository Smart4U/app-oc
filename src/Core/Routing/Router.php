<?php

namespace App\Core\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;

/**
 * Handles the routes of this application
 *
 * Class Router
 * @package App\Core\Routing
 */
class Router
{

    /**
     * @var FastRouteRouter
     */
    private $router;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * @param string $name
     * @param string $path
     * @param $handler
     */
    public function get(string $name, string $path, $handler): void
    {
        $this->router->addRoute(new ZendRoute($path, $handler, ['GET'], $name));
    }

    /**
     * @param string $name
     * @param string $path
     * @param $handler
     */
    public function put(string $name, string $path, $handler): void
    {
        $this->router->addRoute(new ZendRoute($path, $handler, ['PUT'], $name));
    }

    /**
     * @param string $name
     * @param string $path
     * @param $handler
     */
    public function post(string $name, string $path, $handler): void
    {
        $this->router->addRoute(new ZendRoute($path, $handler, ['POST'], $name));
    }

    /**
     * @param string $name
     * @param string $path
     * @param $handler
     */
    public function delete(string $name, string $path, $handler): void
    {
        $this->router->addRoute(new ZendRoute($path, $handler, ['DELETE'], $name));
    }

    /**
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $routeInfo = $this->router->match($request);
        if ($routeInfo->isSuccess()) {
            return new Route($routeInfo->getMatchedRouteName(), $routeInfo->getMatchedMiddleware(), $routeInfo->getMatchedParams());
        }
        return null;
    }

    /**
     * @param string $name
     * @param array $params
     * @param array $getParams
     * @return string
     */
    public function getURI(string $name, array $params = [], array $getParams = []): string
    {
        $uri = $this->router->generateUri($name, $params);
        if (!empty($getParams)) {
            return $uri . '?' . http_build_query($getParams);
        }
        return $uri;
    }
}
