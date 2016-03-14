<?php

use App\Middleware\AuthMiddleware;

$app->group('/auth', function () {
    $this->get('/login', 'App\Controllers\Admin\AuthController:login')->setName('login');
    $this->post('/login', 'App\Controllers\Admin\AuthController:doLogin')->setName('doLogin');
    $this->get('/logout', 'App\Controllers\Admin\AuthController:logout')->setName('logout');
});