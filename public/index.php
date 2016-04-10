<?php
$GLOBALS['startTime'] = microtime(true);

$app = require_once '../app/bootstrap/app.php';

$event = new App\Source\Events\BaseAppEvent($app);
$app->getContainer()->dispatcher->dispatch('app.beforeRun', $event);

$app->run();

$event = new App\Source\Events\BaseLoggerEvent($app->getContainer()->logger);
$app->getContainer()->dispatcher->dispatch('app.afterRun', $event);
