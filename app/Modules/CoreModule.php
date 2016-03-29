<?php

namespace App\Modules;

use App\Modules\IModule;
use Slim\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * В ядровый модуль войдет
 *  логгер, опции, админка, авторизация, события, пользователи
 *
 */

class CoreModule implements IModule
{
    protected $container;
    protected $app;

    protected static $loaded = false;

    /*public function __construct(Container $container, $app)
    {
    $this->container = $container;
    }*/

    public function checkRequireModule(array $t = [])
    {}

    public function installModule()
    {}

    public function uninstallModule()
    {}

    public function initialization($app)
    {
        $this->container = $app->getContainer();
        $this->app = $app;
        
        $this->container['dispatcher'] = function ($c) {
            return new EventDispatcher();
        };

        $this->container->dispatcher->dispatch('module.core.beforeInitialization');
    }

    public function afterInitialization(){
        self::$loaded = true;
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

        $this->container['view'] = function ($c) use ($config) {
            $view = new \Slim\Views\Twig($config['view']['template_path'], $config['view']['twig']);

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
        $app = $this->app;
        $this->container->dispatcher->addListener('app.beforeRun', function ($event) use ($app) {
            $app->add('App\Middleware\CoreFirstLastMiddleware:core');
        }, -1000);
    }

    public static function isInitModule()
    {
        return (bool)self::$loaded;
    }

    public static function getName()
    {
        return "core";
    }
}
