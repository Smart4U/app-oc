<?php
namespace Tests\Core\Routing;

use App\Core\Routing\Route;
use App\Core\Routing\Router;
use GuzzleHttp\Psr7\ServerRequest;

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    /**
     * @var Router
     */
    private $router;


    public function setUp(){
        $this->router = new Router();
    }

    public function testValidGetMethod() {
        $request = new ServerRequest('GET', '/', [], null, 1.1);
        $this->router->get('home', '/', function() { return 'homepage'; });
        $routeInfo = $this->router->match($request);

        $this->assertInstanceOf(Route::class, $routeInfo);
        $this->assertEquals('home', $routeInfo->getRouteName());
        $this->assertEquals('homepage', call_user_func_array($routeInfo->getRouteHandler(), [$request]));
    }

    public function testInvalidGetMethod() {
        $request = new ServerRequest('GET', '/notfound', [], null, 1.1);
        $this->router->get('home', '/', function() { return 'homepage'; });

        $this->assertNull($this->router->match($request));
    }

    public function testValidGetMethodWithParamsNotEmpty() {
        $request = new ServerRequest('GET', '/article/1-LE-slug-1', [], null, 1.1);
        $this->router->get('blog.show', '/article/{slug:[a-zA-Z0-9\-]+}-{id:\d+}', function() { return 'article with id: 1 - slug: 1-LE-slug'; });
        $route = $this->router->match($request);

        $this->assertEquals('blog.show', $route->getRouteName());
        $this->assertEquals('article with id: 1 - slug: 1-LE-slug', call_user_func_array($route->getRouteHandler(), [$request]));
        $this->assertEquals(['slug' => '1-LE-slug', 'id' => '1'], $route->getRouteParams());
    }

    public function testInvalidGetMethodWithParamsNotEmpty() {
        $request = new ServerRequest('GET', '/article/1-LE-slug-1', [], null, 1.1);
        $this->router->get('blog.show', '/article/{slug:[a-zA-Z0-9\-]+}-{id:\d+}', function() { return 'article with id: 1 - slug: 1-LE-slug'; });
        $route = $this->router->match($request);

        $this->assertEquals('blog.show', $route->getRouteName());
        $this->assertEquals('article with id: 1 - slug: 1-LE-slug', call_user_func_array($route->getRouteHandler(), [$request]));
        $this->assertEquals(['slug' => '1-LE-slug', 'id' => '1'], $route->getRouteParams());
    }

    public function testInvalidGetMethodWithIncorrectParams() {
        $request = new ServerRequest('GET', '/article/bad_slug-1', [], null, 1.1);
        $this->router->get('blog.show', '/article/{slug:[a-zA-Z0-9\-]+}-{id:\d+}', function() { return 'article 1'; });
        $this->assertNull(null, $this->router->match($request));
    }

    public function testGetURIRoute() {
        $this->router->get('home', '/', function() { return 'homepage'; });
        $this->router->get('blog.show', '/article/{slug:[a-zA-Z0-9\-]+}-{id:\d+}', function() { return 'article 1'; });

        $this->assertEquals('/', $this->router->getURI('home'));
        $this->assertEquals('/article/le-slug-1', $this->router->getURI('blog.show', ['slug' => 'le-slug', 'id' => 1]));
    }

    public function testGetURIRouteWithQueryParams() {
        $this->router->get('home', '/', function() { return 'homepage'; });
        $this->router->get('blog.show', '/article/{slug:[a-zA-Z0-9\-]+}-{id:\d+}', function() {return 'article 1'; });

        $uriWithoutQueryParams = $this->router->getURI('home');
        $uriWithQueryParams = $this->router->getURI('blog.show', ['slug' => 'le-slug', 'id' => 1],['page' => 2]);

        $this->assertEquals('/', $uriWithoutQueryParams);
        $this->assertEquals('/article/le-slug-1?page=2', $uriWithQueryParams);
    }

    public function testValidPutMethod() {
        $request = new ServerRequest('PUT', '/category/1', [], null, 1.1);
        $this->router->put('category.update', '/category/{id:\d+}', function() { return 'category 1'; });
        $routeInfo = $this->router->match($request);

        $this->assertInstanceOf(Route::class, $routeInfo);
        $this->assertEquals('category.update', $routeInfo->getRouteName());
        $this->assertEquals('category 1', call_user_func_array($routeInfo->getRouteHandler(), [$request]));
    }

    public function testInvalidPutMethod() {
        $request = new ServerRequest('PUT', '/category/badID', [], null, 1.1);
        $this->router->put('category.update', '/category/{id:\d+}', function() { return 'category 1'; });

        $this->assertNull($this->router->match($request));
    }

}