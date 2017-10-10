<?php


namespace App\Core\Database\Driver;

use PDO;

/**
 * Class Sqlite
 * @package App\Core\Database\Driver
 */
class Sqlite extends Base
{

    /**
     * @var null|PDO
     */
    protected $pdo = null;

    /**
     * @var array
     */
    protected $required = ['name'];

    public function createConnection(array $settings)
    {
        $this->pdo = new PDO('sqlite:'.$settings['name']);
    }
}
