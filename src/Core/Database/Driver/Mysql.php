<?php

namespace App\Core\Database\Driver;

use PDO;

/**
 * Class Mysql
 * @package App\Core\Database\Driver
 */
class Mysql extends Base
{


    /**
     * @var PDO|null
     */
    protected $pdo = null;

    /**
     * @var array
     */
    protected $required = ['hostname', 'database', 'username', 'password'];

    /**
     * @var array
     */
    protected $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode = STRICT_ALL_TABLES',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];

    /**
     * @param array $settings
     * @return PDO
     */
    public function createConnection(array $settings = []): PDO
    {
        return new PDO($this->dsn($settings), $settings['username'], $settings['password'], $this->options);
    }

    /**
     * @param array $settings
     * @return string
     */
    protected function dsn(array $settings): string
    {
        $charset = empty($settings['charset']) ? 'utf8' : $settings['charset'];
        $dsn = 'mysql:host='.$settings['hostname'].';dbname='.$settings['database'].';charset='.$charset;

        if (! empty($settings['port'])) {
            $dsn .= ';port='.$settings['port'];
        }

        return $dsn;
    }
}
