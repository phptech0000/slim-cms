<?php

session_start();

define('ROOT_PATH', __DIR__ . '/../../');

define('APP_PATH', ROOT_PATH . 'app/');
define('CACHE_PATH', ROOT_PATH . 'cache/');
define('VENDOR_PATH', ROOT_PATH . 'vendor/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('RESOURCE_PATH', ROOT_PATH . 'resource/');

define('MODULE_PATH', APP_PATH . 'modules/');

require VENDOR_PATH . 'autoload.php';
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
foreach (glob(APP_PATH . 'config/*.php') as $configFile) {
    $config += require_once $configFile;
}

if ($config['slim']['settings']['debug']) {
    error_reporting(E_ALL ^ E_NOTICE);
}

$container = new \Slim\Container($config['slim']);
$container->config = $config;

$app = new \Slim\App($container);

$modules = App\Modules\ModuleManager::getInstance($container, $app);
$modules->registerModule(new App\Modules\CoreModule());

$modules->coreInit()->boot();

//--- Register manual module ---//
$modules->registerModule(new App\Modules\ShowFieldAdminPanelModule());
$modules->registerModule(new App\Modules\SectionsModule());
$modules->registerModule(new App\Modules\BreadcrumbModule());

foreach (glob(APP_PATH . 'routers/base.php') as $configFile) {
    require_once $configFile;
}

$modules->boot();

$container->dispatcher->addListener('middleware.core.after', function ($event) {
    $event->getLogger()->addInfo("Info - Core middleware after");
});

$container->dispatcher->addListener('middleware.core.before', function ($event) {
    $event->getLogger()->addInfo("Info - Core middleware before");
});

/*
foreach( $app->getContainer()->get('installedModules') as $module){
$modules->registerModule(new App\Modules\$module->className());
}
$modules->boot();
 */

return $app;
