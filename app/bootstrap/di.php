<?php
use \Illuminate\Database\Capsule\Manager as DB;
use Symfony\Component\EventDispatcher\EventDispatcher;

// Register service provider
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('slimcms_core');

    $handler = new \Monolog\Handler\StreamHandler(ROOT_PATH."../log/app.log");
    if( $c['settings']['log_system'] == 'db'){
        $handler = new MySQLHandler\MySQLHandler(DB::connection()->getPdo(), "logging");
        if( DB::connection()->getDriverName() == 'sqlite' )
            $handler = new App\Helpers\SqliteMonologHandler(DB::connection()->getPdo(), "logging");
    }

    $logger->pushHandler($handler);
    return $logger;
};

$container['cache'] = function () {
    return new \Slim\HttpCache\CacheProvider();
};

$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$container['flashMess'] = function () use ($container) {
    return $container['flash']->getMessages();
};

$container['view'] = function ($c) use ($config) {
    $view = new \Slim\Views\Twig($config['view']['template_path'], $config['view']['twig']);

    // Instantiate and add Slim specific extension
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));

    return $view;
};

$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $heandler = new App\Controllers\Sites\UniversalPageController($c);
        return $heandler->notFound($request, $response)->withStatus(404);
    };
};

$container['systemOptions'] = function ($c) {
    return new App\Source\Facade\OptionsFacade(App\Models\Options::where('options_group_id', 1)->get());
};

$container['dispatcher'] = function ($c) {
    return new EventDispatcher();
};

/*$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['view']->render($response, 'public/main/pages/404.twig')->withStatus(404);
    };
};*/