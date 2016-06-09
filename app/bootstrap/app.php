<?php

use App\Source\ModuleInitializer;
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

$app = new App($container);

$mm = ModuleInitializer::getInstance($app, $classLoader);
$mm->registerModuleNames(['core', 'sections', 'breadcrumb', 'customizeradminpanel']);
$mm->initModules();
$mm->bootCore();
$mm->boot();
//p($mm->getAllLoadedModuleClassName());
//$mm->registerModuleNames([]);
//$mm->initModules();


/*$modules = ModuleManager::getInstance($container, $app);
$modules->registerModule(new Core\CoreModule());

$modules->coreInit()->boot();*/
/*
//--- Register manual module ---//
$modules->registerModule(new ShowFieldAdminPanelModule());
$modules->registerModule(new SectionsModule());
$modules->registerModule(new BreadcrumbModule());

/*foreach (glob(APP_PATH . 'routers/base.php') as $configFile) {
    require_once $configFile;
}*/

//$modules->boot();

$container->dispatcher->addListener('middleware.core.after', function ($event) {
    $event->getLogger()->info("Core middleware after");
});

$container->dispatcher->addListener('middleware.core.before', function ($event) {
    $event->getLogger()->info("Core middleware before");
});

/*
foreach( $app->getContainer()->get('installedModules') as $module){
$modules->registerModule(new App\Modules\$module->className());
}
$modules->boot();
 */

return $app;
