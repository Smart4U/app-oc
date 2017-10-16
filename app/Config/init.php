<?php

return [
    \MyApp\Routes\RenderRoute::initRoutesBundle(),
    \MyApp\Controllers\PagesController::class => \DI\object(),
    \MyApp\Controllers\ContactController::class => \DI\object(),
    \MyApp\Controllers\PostsController::class => \DI\object()->constructor(\App\Core\Database\Database::class)

];