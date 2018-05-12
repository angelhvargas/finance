<?php
// DIC configuration

$container = $app->getContainer();
$app = new \Slim\App($container);

//added cache
$container['cache'] = function () {
    return new \Slim\HttpCache\CacheProvider();
};

//csfr protection
$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};

//rebind app
$app->add(new \Slim\HttpCache\Cache('public', 86400));

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

//eloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db'], "default");
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};