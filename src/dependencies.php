<?php
// DIC configuration

$container = $app->getContainer();
$app = new \Slim\App($container);

//added cache cache container
$container['cache'] = function () {
    return new \Slim\HttpCache\CacheProvider();
};

//register csfr protection filter/middleware
$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};

//register Http cache
$app->add(new \Slim\HttpCache\Cache('public', 86400));

// register view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// Register twig view
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig($container['settings']['view']['template_path'], [
        'cache' => $container['settings']['view']['cache_path'],
        'debug' => $container['settings']['view']['debug']
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
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