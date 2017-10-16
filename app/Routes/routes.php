<?php

use MyApp\Controllers\PagesController;
use MyApp\Controllers\PostsController;
use MyApp\Controllers\ContactController;

// PAGES
$router->get('pages.home', '/', [PagesController::class, 'home']);
$router->get('pages.services', '/services', [PagesController::class, 'services']);
$router->get('pages.about', '/a-propos', [PagesController::class, 'about']);

// BLOG
$router->get('posts.index', '/articles', [PostsController::class, 'index']);
$router->get('posts.show', '/article/{slug: [a-zA-Z0-9\-]+}-{id:\d+}', [PostsController::class, 'show']);

// CONTACT
$router->get('contact.index', '/contact', [ContactController::class, 'index']);
$router->post('contact.send', '/contact', [ContactController::class, 'send']);

