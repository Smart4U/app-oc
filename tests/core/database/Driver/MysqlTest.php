<?php

namespace Tests\Core\Database\Driver;

use PDO;
use App\Core\Database\Driver\Mysql;

use PHPUnit\Framework\TestCase;

class MysqlTest extends TestCase
{
    private $settings;

    public function setUp() {
        $env = new \Dotenv\Dotenv(dirname(dirname(dirname(dirname(__DIR__)))));
        $env->load();
        $this->settings = require('config/database.php');
    }

    public function testLogicExceptionWithMissingParameters() {
        $this->expectException(\LogicException::class);
        new Mysql([]);
    }

    public function testCreateConnectionGiveInstanceOfPdo() {
        $mysql = new Mysql($this->settings['production']);

        $this->assertInstanceOf(Mysql::class, $mysql);
        $this->assertInstanceOf(PDO::class, $mysql->getConnection());
    }
}