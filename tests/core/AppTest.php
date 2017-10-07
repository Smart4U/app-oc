<?php

namespace Tests\Core;


use App\Core\App;
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

    public function testIfMethodRunReturnAString() {
        $this->assertEquals('app loaded...', $this->app->run());
    }

}