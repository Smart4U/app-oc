<?php

namespace App\Core\Routing;

use App\Core\Renderer\RendererInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
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
            return new Route(
                $routeInfo->getMatchedRouteName(),
                $routeInfo->getMatchedMiddleware(),
                $routeInfo->getMatchedParams()
            );
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

    /**
     * @param string $uri
     * @param array $params
     * @param array $queryParams
     */
    public function redirect(string $uri, array $params = [], array $queryParams = [])
    {
        $uri = $this->getURI($uri, $params, $queryParams);

        $routeInfo = $this->router->match(new ServerRequest('GET', $uri, [], null, 1.1));
        if ($routeInfo->isSuccess()) {
            header('Location: ' . $uri);
            exit();
        }

        throw new \RuntimeException('Invalid redirect route');
    }

    /**
     * @param ServerRequestInterface $request
     * @param ContainerInterface $container
     * @param RendererInterface $renderer
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request, ContainerInterface $container, RendererInterface $renderer) {

        if($this->isDuplicateContent($request)){
            return new Response(301, ['Location ' => substr($request->getUri()->getPath(), 0, -1)], null);
        }

        $route = $this->match($this->buildCorrectMethod($request));
        if (is_null($route)) {
            return $this->notFound($renderer);
        }

        $request = $this->addRequestParameters($request, $route);
        [$controller, $action] = $route->getRouteHandler();

        return $this->found($request, $container->get($controller)->$action($request));
    }


    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    private function buildCorrectMethod(ServerRequestInterface $request) :ServerRequestInterface {
        $parseBody = $request->getParsedBody();
        if (array_key_exists('http_method', $parseBody) && in_array($parseBody['http_method'], ['PUT', 'DELETE'])) {
            $request = $request->withMethod($parseBody['http_method']);
            return $request;
        }
        return $request;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Route $route
     * @return ServerRequestInterface
     */
    private function addRequestParameters(ServerRequestInterface $request, Route $route) :ServerRequestInterface {
        $params = $route->getRouteParams();
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);

        return $request;
    }


    /**
     * @param $request
     * @param string $handlerResponse
     * @return Response
     * @throws \Exception
     */
    private function found($request, $handlerResponse) {

        if(is_string($handlerResponse)) {
            return new Response(200, $request->getHeaders(), $handlerResponse);
        }
        if($handlerResponse instanceof ServerRequestInterface) {
            return new Response(200, $request->getHeaders(), $handlerResponse->getBody()->getContents());
        }

        throw new \Exception('This response is not correct.');
    }

    /**
     * @param RendererInterface $renderer
     * @return ResponseInterface
     */
    public function notFound(RendererInterface $renderer): ResponseInterface {
        return new Response(404, [], (string)$renderer->render('errors/400/404.twig'));
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    private function isDuplicateContent(ServerRequestInterface $request) {
        $currentURI = $request->getUri()->getPath();
        if ($currentURI[-1] === '/' && $currentURI !== '/') {
            return true;
        }
        return false;
    }

}
