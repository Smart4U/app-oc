<?php


namespace MyApp\Routes;


/**
 * Add routes for MyApp
 * Class RenderRoute
 * @package MyApp\Routes
 */
class RenderRoute
{

    /**
     * @var string
     */
    private static $routesFile = __DIR__ . '/routes.php';


    /**
     * @return string
     */
    public static function initRoutesBundle() {
        if(file_exists(self::$routesFile)) {
            return self::$routesFile;
        }
    }

}