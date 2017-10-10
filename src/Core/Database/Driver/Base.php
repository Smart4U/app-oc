<?php


namespace App\Core\Database\Driver;

use PDO;
use LogicException;

/**
 * Class Base
 * @package App\Core\Database\Driver
 */
abstract class Base
{

    /**
     * @var null|PDO
     */
    protected $pdo = null;


    /**
     * @var array
     */
    protected $required = [];


    /**
     * @var array
     */
    protected $options = [];


    /**
     * @param array $settings
     * @return mixed
     */
    abstract public function createConnection(array $settings);


    /**
     * Base constructor.
     * @param array $settings
     */
    public function __construct(array $settings)
    {

        foreach ($this->required as $attribute) {
            if (! isset($settings[$attribute])) {
                throw new LogicException('Initialize database: ' . $attribute . ' -> his parameter does not exist');
            }
        }

        $this->pdo = $this->createConnection($settings);
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     * @return void
     */
    public function closeConnection(): void
    {
        $this->pdo = null;
    }
}
