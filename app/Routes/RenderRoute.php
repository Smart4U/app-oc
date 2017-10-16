<?php


namespace MyApp\Routes;


class RenderRoute
{

    private static $routesFile = __DIR__ . '/routes.php';


    public static function initRoutesBundle() {
        if(file_exists(self::$routesFile)) {
            return self::$routesFile;
        }
    }

}