<?php
$GLOBALS['startTime'] = microtime(true);

use Slim\Container;
use App\Source\ModuleLoader;
use App\Helpers\ConfigWorker;
use App\Source\Factory\AppFactory;
use App\Source\Decorators\SlimCMS;

session_start();

define('ROOT_PATH', realpath(__DIR__ . '/../../') . '/');

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

$clearCache = false;
if (isset($_REQUEST['clear_cache'])) {
    $clearCache = true;
}

/** include Config files */
$config += ConfigWorker::init([], $clearCache)->all();

if( !isset($config['slim']) ){
    $container = new Container(['debug' => true, 'use_log' => false, 'determineRouteBeforeAppMiddleware' => true, 'displayErrorDetails' => true]);
    $app = AppFactory::setInstance(new SlimCMS($container));
    ModuleLoader::bootEasyModule(new Modules\SystemInstaller\Module());
    return $app;
}

if ($config['slim']['settings']['debug']) {
    error_reporting(E_ALL ^ E_NOTICE);
}

$container = new Container($config['slim']);
$container->config = ConfigWorker::getConfig();

$app = AppFactory::setInstance(new SlimCMS($container));

ModuleLoader::bootCore(new \Modules\Core\Module());

$moduleManager = new \App\Source\ModuleManager(MODULE_PATH);
$moduleManager->init($clearCache)->registerModules();

ModuleLoader::bootLoadModules($moduleManager->getModules());

unset($moduleManager);

return $app;