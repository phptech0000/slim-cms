<?php

namespace App\Modules;

use App\Modules\AModule;
use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\App;
use Slim\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * В ядровый модуль войдет
 *  опции(+), логгер(+), csrf(+), флеш сообщения(+), админка()+авторизация(), события(+), пользователи(), модули()
 *
 */

class CoreModule extends AModule
{
    const MODULE_NAME = 'core';

    public function initialization(App $app)
    {
        parent::initialization($app);

        $this->registerDB();

        $this->container['dispatcher'] = function ($c) {
            return new EventDispatcher();
        };

        $this->container['router'] = function () {
            return new \App\Source\Decorators\RouteDecorator;
        };

        $this->container->dispatcher->dispatch('module.core.beforeInitialization');
    }

    public function registerRoute()
    {
        $this->app->get('/', function ($req, $res) {$res->getBody()->write("Core module load");})->setName('home');
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

            $view->addExtension(new \Twig_Extension_Debug());

            return $view;
        };

    }

    public function registerMiddleware()
    {
        $this->container->dispatcher->addListener('app.beforeRun', function ($event) {
            $event->getApp()->add('App\Middleware\CoreFirstLastMiddleware:core');
        }, -1000);
    }

    public function afterInitialization()
    {
        parent::afterInitialization();

        $modules = \App\Modules\ModuleManager::getInstance();

        $modules->registerModule(new \App\Modules\LoggerModule());
        $modules->registerModule(new \App\Modules\SystemOptionsModule());
        $modules->registerModule(new \App\Modules\CSRFModule());
        $modules->registerModule(new \App\Modules\FlashModule());
        $modules->registerModule(new \App\Modules\AuthModule());
        $modules->registerModule(new \App\Modules\AdminPanelModule());
        $modules->registerModule(new \App\Modules\PublicModule());

        if (isset($this->container['settings']['protectDoubleRouteRegister']) &&
            $this->container['settings']['protectDoubleRouteRegister']
        ) {
            $this->routerControlSystem();
        }
    }

    protected function registerDB()
    {
        $capsule = new Capsule();

        $capsule->addConnection($this->container->config['db'][$this->container->config['slim']['db_driver']]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->container['db'] = function ($c) {
            return new Capsule();
        };
    }

    protected function routerControlSystem()
    {
        $this->container->dispatcher->addListener('app.beforeRun', function ($event) {
            \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) use ($event) {
                foreach ($event->getContainer()->get('router')->getRoutes() as $route) {
                    try {
                        $r->addRoute($route->getMethods(), $route->getPattern(), $route->getIdentifier());
                    } catch (\FastRoute\BadRouteException $e) {
                        $event->getLogger()->addWarning('Register router: ' . $e->getMessage());
                        $event->getContainer()->get('router')->removeRoute($route->getIdentifier());
                        continue;
                    }
                }
            });
        }, 1000);
    }
}
