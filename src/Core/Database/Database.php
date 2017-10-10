<?php

namespace App\Core\Database;

use App\Core\Database\Driver\Sqlite;
use App\Core\Database\Driver\Mysql;
use App\Core\Database\Driver\DriverFactory;


/**
 * Class Database
 * @package App\Core\Database
 */
class Database
{

    /**
     * @var Mysql|Sqlite
     */
    private $driver;


    /**
     * Database constructor.
     * @param array $settings
     */
    public function __construct(array $settings = []) {
        $this->driver = DriverFactory::getDriver($settings);
    }

    /**
     * @return void
     */
    public function __destruct() {
        $this->closeConnection();
    }

    /**
     * @return mixed
     */
    public function getConnection() {
        return $this->driver->getConnection();
    }

    /**
     * Close connection
     * @retutn void
     */
    public function closeConnection(): void {
        $this->driver->closeConnection();
    }

}