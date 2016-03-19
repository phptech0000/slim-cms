<?php
$startTime = microtime(true);

$app = require_once('../app/bootstrap/app.php');

$app->getContainer()->get('logger')->addInfo("Info - start application: ", []);

$app->run();

$workTime = round((microtime(true) - $startTime), 3);

$app->getContainer()->get('logger')->addInfo("Statistic - work time: ", [$workTime.'s']);

$app->getContainer()->get('logger')->addInfo("Statistic - memory usage: ", [memoryFormat(memory_get_usage())]);

$app->getContainer()->get('logger')->addInfo("Statistic - max memory usage: ", [memoryFormat(memory_get_peak_usage())]);

$app->getContainer()->get('logger')->addInfo("Info - end application: ", []);
$app->getContainer()->get('logger')->addInfo("", []);