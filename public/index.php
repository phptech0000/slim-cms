<?php
$GLOBALS['startTime'] = microtime(true);

$app = require_once '../app/bootstrap/app.php';

$app->getContainer()->dispatcher->dispatch('app.beforeRun');

$app->run();

$app->getContainer()->dispatcher->dispatch('app.afterRun');
