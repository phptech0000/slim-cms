<?php

use App\Source\Factory\AppFactory;
use App\Source\ModuleLoader;
use Slim\App;
use Slim\Container;
use App\Helpers\ConfigWorker;

session_start();

define('ROOT_PATH', realpath(__DIR__ . '/../../').'/');

define('APP_PATH', ROOT_PATH . 'app/');
define('CACHE_PATH', ROOT_PATH . 'cache/');
define('VENDOR_PATH', ROOT_PATH . 'vendor/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('RESOURCE_PATH', ROOT_PATH . 'resource/');

define('MODULE_PATH', ROOT_PATH . 'modules/');

$classLoader = require VENDOR_PATH . 'autoload.php';

require APP_PATH . 'Helpers/functions.php';

/**
 * Load the configuration
 */
$config = array(
    'path.root' => ROOT_PATH,
    'path.cache' => CACHE_PATH,
    'path.public' => PUBLIC_PATH,
    'path.app' => APP_PATH,
    'path.module' => MODULE_PATH,
    'path.resource' => RESOURCE_PATH,
);

/** include Config files */
$config += ConfigWorker::init([], true)->all();

if ($config['slim']['settings']['debug']) {
    error_reporting(E_ALL ^ E_NOTICE);
}

$container = new Container($config['slim']);
$container->config = ConfigWorker::getConfig();

$app = AppFactory::setInstance(new App($container));
ModuleLoader::bootCore(new \Modules\Core\Module());

$moduleLoader = new \App\Source\ModuleManager(MODULE_PATH);
$moduleLoader->init()->registerModules();

ModuleLoader::bootLoadModules($moduleLoader->getModules());

unset($moduleLoader);

$container->dispatcher->addListener('middleware.core.after', function ($event) {
    $event->getLogger()->info("Core middleware after");
});

$container->dispatcher->addListener('middleware.core.before', function ($event) {
    $event->getLogger()->info("Core middleware before");
});

return $app;
