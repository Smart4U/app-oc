<?php

namespace App\Core;

use App\Core\Routing\Router;
use GuzzleHttp\Psr7\Response;
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
        $router = $this->container->get(Router::class);
        $router->get('page.home', '/', function () {
            return new Response(200, [], 'page homepage');
        });
        $router->get('page.about', '/a-propos', function () {
            return new Response(200, [], 'page à-propos');
        });
        $router->get('page.about', '/contact', function () {
            return new Response(200, [], 'page contact');
        });
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $currentURI = $request->getUri()->getPath();

        // Avoid duplicate content
        if ($currentURI[-1] === '/' && $currentURI !== '/') {
            return new Response(301, ['Location' => substr($currentURI, 0, -1)], null);
        }

        // Correct Method for request PUT or DELETE
        $parseBody = $request->getParsedBody();
        if (array_key_exists('http_method', $parseBody) && in_array($parseBody['http_method'], ['PUT', 'DELETE'])) {
            $request = $request->withMethod($parseBody['http_method']);
        }

        // Get the route associate to the currentURI
        $route = $this->container->get(Router::class)->match($request);

        // Page Not Found
        if (is_null($route)) {
            return new Response(404, [], 'Not Found ;(');
        }

        // Push all params in HTTP Request
        $params = $route->getRouteParams();
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);


        // Get the response handler :ResponseInterface
        return call_user_func_array($route->getRouteHandler(), [$request]);
    }
}
