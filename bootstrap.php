<?php

define('ROOT', __DIR__);

/**
 * Autoloader PSR-4 (Composer)
 */
require ROOT . '/vendor/autoload.php';


/**
 * Environment variables (dotfile .env)
 */
$env = new \Dotenv\Dotenv(ROOT);
$env->load();


/**
 * ContainerBuilder
 */
$builder = new \DI\ContainerBuilder();


/**
 * Global Configuration
 */
$config = array_slice(scandir(ROOT . '/config'), 2);
foreach ($config as $value) {
    $builder->addDefinitions([substr($value, 0, -4) => require ROOT . '/config/' . $value]);
}


/**
 * The kernel contains the global dependencies
 */
$kernel = ROOT . '/kernel.php';
$builder->addDefinitions($kernel);


/**
 * The container is the core of this application
 */
return $builder->build();
