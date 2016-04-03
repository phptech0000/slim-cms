<?php

namespace App\Modules;

use Slim\App;
use App\Middleware\AuthMiddleware;
use App\Source\RouteSystem\AdminResource;
use App\Source\RouteSystem\AdminRouteCollection;
use App\Helpers\SessionManager as Session;

class AdminPanelModule extends AModule
{
    const MODULE_NAME = 'admin_panel';

    public function initialization(App $app)
    {
        parent::initialization($app);
    }

    public function registerDi()
    {
    	$this->container['adminMenu'] = function ($c) {
		    return null;
		};
    }

    public function registerMiddleware()
    {
    }

    public function registerRoute()
    {
        $this->adminPanelRouteRegister();

        $this->app->group('/admin', function () {
            $this->get('/', function($req, $res){
                return $res->withStatus(301)->withHeader('Location', $this->router->pathFor('dashboard'));
            });
            $this->get('/dashboard', 'App\Controllers\Admin\DashboardController:index')->setName('dashboard');

            if( !Session::has('auth') || !Session::get('auth') ){
                $this->get('/{page:.*}', function($req, $res){
                return $res->withStatus(301)->withHeader('Location', $this->router->pathFor('dashboard'));
                });
            }
        })->add( new AuthMiddleware() );
    }

    public function afterInitialization(){
        parent::afterInitialization();

        $this->container->dispatcher->addListener('app.beforeRun', function ($event){
            $event->getApp()->group('/admin', function () {
                AdminRouteCollection::register($this);
            })->add( new AuthMiddleware() );
        }, -980);
    }

    protected function adminPanelRouteRegister(){
        if( Session::has('auth') && Session::get('auth') ){
            AdminRouteCollection::add(new AdminResource('pages'));
            AdminRouteCollection::add(new AdminResource('users'));
            AdminRouteCollection::add(new AdminResource('groups'));
        }
    }
}
