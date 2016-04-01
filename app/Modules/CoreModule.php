<?php

namespace App\Modules;

use App\Modules\AModule;
use Slim\Container;
use Slim\App;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * В ядровый модуль войдет
 *  опции, админка, авторизация, события(+), пользователи, модули
 *
 */

class CoreModule extends AModule
{
    const MODULE_NAME = 'core';

    public function initialization(App $app)
    {
        parent::initialization($app);
        
        $this->container['dispatcher'] = function ($c) {
            return new EventDispatcher();
        };

        $this->container->dispatcher->dispatch('module.core.beforeInitialization');
    }

    public function registerRoute()
    {
        $this->app->get('/', function(){});
    }

    public function registerDi()
    {
        $this->container['flash'] = function () {
            return new \Slim\Flash\Messages();
        };

        $this->container['view'] = function ($c) {
            $view = new \Slim\Views\Twig($c->config['view']['template_path'], $c->config['view']['twig']);

            // Instantiate and add Slim specific extension
            $view->addExtension(new \Slim\Views\TwigExtension(
                $c['router'],
                $c['request']->getUri()
            ));

            return $view;
        };
    }

    public function registerMiddleware()
    {
        $this->container->dispatcher->addListener('app.beforeRun', function ($event){
            $event->getApp()->add('App\Middleware\CoreFirstLastMiddleware:core');
        }, -1000);
    }
}
