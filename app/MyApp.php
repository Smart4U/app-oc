<?php

namespace MyApp;

use App\Core\Routing\Router;

/**
 * Class MyApp
 * @package MyApp
 */
class MyApp {

    /**
     * MyApp constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        require Routes\RenderRoute::initRoutesBundle();
    }

}