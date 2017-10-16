<?php

/**
 * The kernel that contains the dependencies of my project
 */


use function DI\{get,object};

return [

    // SESSION
    App\Core\Session\SessionInterface::class => \DI\object(\App\Core\Session\Session::class),
    \App\Core\Notify\Flash::class => \DI\object(),


    // ROUTING
    \App\Core\Routing\Router::class => object(),
    \App\Core\Routing\Route::class => object(),

    // CONTROLLER
    \App\Core\Controller\Controller::class => object(),

    // DATABASE
    \App\Core\Database\Database::class => object()->constructor(get('database')),

    // VIEWS
    'views.path' => __DIR__ . '/resources/views',
    \App\Core\Renderer\RendererInterface::class => \DI\factory(\App\Core\Renderer\TwigRendererFactory::class),

    'twig.extensions' => [
        \DI\get(App\Core\Twig\FrontHelperTwigExtension::class),
        \DI\get(App\Core\Twig\RouterTwigExtension::class),
        \DI\get(App\Core\Twig\FlashTwigExtension::class),
        \DI\get(App\Core\Twig\PagerFantaTwigExtension::class),
        \DI\get(App\Core\Twig\TextTwigExtension::class),
        \DI\get(App\Core\Twig\FormHelperTwigExtension::class)

    ],

];