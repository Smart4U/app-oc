<?php

use MyApp\Controllers\Admin\PostsAdminController;
use MyApp\Controllers\Admin\DashboardController;
use MyApp\Controllers\PagesController;
use MyApp\Controllers\PostsController;
use MyApp\Controllers\ContactController;

// PAGES
$router->get('pages.home', '/', [PagesController::class, 'home'], 'home');

$router->get('pages.services', '/services', [PagesController::class, 'services']);
$router->get('pages.about', '/a-propos', [PagesController::class, 'about']);

// BLOG
$router->get('posts.index', '/articles', [PostsController::class, 'index']);
$router->get('posts.show', '/article/{slug: [a-zA-Z0-9\-]+}-{id:\d+}', [PostsController::class, 'show']);

// CONTACT
$router->get('contact.index', '/contact', [ContactController::class, 'index']);
$router->post('contact.send', '/contact', [ContactController::class, 'send']);

// ADMIN
$router->get('admin.dashboard', '/admin', [DashboardController::class, 'dashboard']);

// ADMIN BLOG
$router->get('admin.posts.index', '/admin/posts', [PostsAdminController::class, 'index']);
$router->get('admin.posts.create', '/admin/posts/create', [PostsAdminController::class, 'create']);
$router->post('admin.posts.store', '/admin/posts/create', [PostsAdminController::class, 'store']);
$router->get('admin.posts.edit', '/admin/posts/{id:\d+}/edit', [PostsAdminController::class, 'edit']);
$router->put('admin.posts.update', '/admin/posts/{id:\d+}', [PostsAdminController::class, 'update']);
$router->delete('admin.posts.destroy', '/admin/posts/{id:\d+}', [PostsAdminController::class, 'destroy']);
