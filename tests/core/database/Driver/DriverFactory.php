<?php

namespace Tests\Core\Database\Driver;


use PHPUnit\Framework\TestCase;

class DriverFactory extends TestCase
{

    private $settings;

    public function setUp() {
        $env = new \Dotenv\Dotenv(dirname(dirname(dirname(dirname(__DIR__)))));
        $env->load();
        $this->settings = require('config/database.php');
    }

    public function testGetDriver() {

        $this->expectException(\LogicException::class, \App\Core\Database\Driver\DriverFactory::getDriver([]));

        $driver = \App\Core\Database\Driver\DriverFactory::getDriver($this->settings);
        $this->assertEquals('mysql', $driver);

    }

}