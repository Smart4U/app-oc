<?php

namespace Tests\Core;


use App\Core\App;

use App\Core\Routing\Router;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{

    /**
     * @var App
     */
    private $app;

    public function setUp()
    {
        $this->app = new App(require_once dirname(dirname(__DIR__)) . '/bootstrap.php');
    }

    public function testIfMethodRunReturnAInstanceOfResponseInterface() {
        $request = new ServerRequest(200, '/', [], null);
        $router = new Router();
        $router->get('page.home', '/', function () { return new Response(200, [], 'homepage'); });

        $this->assertInstanceOf(ServerRequest::class, $request);
        $this->assertInstanceOf(Router::class, $router);
    }

}