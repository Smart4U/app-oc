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
        $this->app = new App();
    }

    public function testIfMethodRunReturnCorrectReponse() {
        $this->assertEquals('app loaded...', $this->app->run());
    }

}