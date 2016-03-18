<?php

use App\Middleware\AuthMiddleware;

use App\Source\RouteSystem\AdminResource;
use App\Source\RouteSystem\AdminRouteCollection;
use App\Helpers\SessionManager as Session;

if( Session::has('auth') && Session::get('auth') ){
    AdminRouteCollection::add(new AdminResource('pages'));
    AdminRouteCollection::add(new AdminResource('users'));
    AdminRouteCollection::add(new AdminResource('groups'));
    AdminRouteCollection::add(new AdminResource('options', 'App\Controllers\Admin\OptionsController'));
    AdminRouteCollection::add(new AdminResource('group_options'));
}

$app->group('/admin', function () use ($app) {
    $this->get('/', function($req, $res){
        return $res->withStatus(301)->withHeader('Location', $this->router->pathFor('dashboard'));
    });
    $this->get('/dashboard', 'App\Controllers\Admin\DashboardController:index')->setName('dashboard');

    if( !Session::has('auth') || !Session::get('auth') )
        $this->get('/{page:.*}', function($req, $res){
        return $res->withStatus(301)->withHeader('Location', $this->router->pathFor('dashboard'));
        });

    AdminRouteCollection::register($app);

})->add( new AuthMiddleware() );