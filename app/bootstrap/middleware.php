<?php

$app->add(new \Slim\HttpCache\Cache('public', 86400));

$app->add(new \Slim\Csrf\Guard);

$app->add(new App\Middleware\CorsMiddleware());

