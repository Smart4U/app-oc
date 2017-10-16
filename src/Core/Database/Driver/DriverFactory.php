<?php


namespace App\Core\Database\Driver;

use LogicException;

/**
 * Class DriverFactory
 * @package App\Core\Database\Driver
 */
class DriverFactory
{

    /**
     * @param array $settings
     * @return Mysql|Sqlite
     */
    public static function getDriver(array $settings)
    {
        if (!isset($settings['default'])
            && !isset($settings[$settings['default']]['driver'])
            && !array_key_exists($settings[$settings['default']], $settings)
        ) {
            throw new LogicException('You must define a database driver');
        }
        $config = $settings[$settings['default']];

        switch ($config['driver']) {
            case 'sqlite':
                return new Sqlite($config);
            case 'mysql':
                return new Mysql($config);
            default:
                throw new LogicException('This database driver is not supported');
        }
    }
}
