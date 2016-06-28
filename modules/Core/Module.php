<?php

namespace Modules\Core;

use Slim\Flash\Messages;
use Slim\Router;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

use App\Source\AModule;
use App\Source\ModuleLoader;
use App\Helpers\SessionManager as Session;
use Modules\Core\Source\MicroModules\AuthModule;
use Modules\Core\Source\MicroModules\CSRFModule;
use Modules\Core\Source\MicroModules\FlashModule;
use Modules\Core\Source\MicroModules\LoggerModule;
use Modules\Core\Source\MicroModules\PublicModule;
use Modules\Core\Source\MicroModules\AdminPanelModule;
use Modules\Core\Source\MicroModules\SystemOptionsModule;
use Modules\Core\Source\MicroModules\CustomizerAdminPanelModule;

/**
 * Base module from use SlimCMS
 * Class CoreModule
 * @package Modules\Core
 */
class Module extends AModule
{
    /**
     * Module name
     */
    const MODULE_NAME = 'Core';

    /**
     * Require module loaded
     * @var array
     */
    public $requireModules = [];

    /**
     * Init module
     */
    public function initialization()
    {
        $this->registerDB();

        $this->container['dispatcher'] = function () {
            return new EventDispatcher();
        };

        $this->container['router'] = function () {
            return new Router();
        };

        $this->container->dispatcher->dispatch('module.core.beforeInitialization');
    }

    /**
     * Register route in slim framework
     */
    public function registerRoute()
    {
        $this->app->get('/', function ($req, $res) {
            $res->getBody()->write("Core module load. You application get ready.");
        })->setName('home');
    }

    /**
     * Register DI container in slim framework
     */
    public function registerDi()
    {
        $this->container['flash'] = function () {
            return new Messages();
        };

        $this->container['view'] = function ($c) {
            $view = new Twig($c->config['view']['template_path'], $c->config['view']['twig']);

            // Instantiate and add Slim specific extension
            $view->addExtension(new TwigExtension(
                $c['router'],
                $c['request']->getUri()
            ));

            $view->addExtension(new \Twig_Extension_Debug());

            return $view;
        };

    }

    /**
     * Register middleware in slim framework
     */
    public function registerMiddleware()
    {
        $this->container->dispatcher->addListener('app.beforeRun', function ($event) {
            $event->getApp()->add('Modules\Core\Source\Libs\Middleware\CoreFirstLastMiddleware:core');
        }, -1000);

        $this->app->add(function($request, $response, $next){
            $response = $response->withAddedHeader('X-Powered-CMS', 'SlimCMS');
            $response = $response->withAddedHeader('X-XSS-Protection', '1; mode=block');
            $response = $response->withAddedHeader('X-Frame-Options', 'SAMEORIGIN');
            $response = $response->withAddedHeader('X-Content-Type-Options', 'nosniff');
            $response = $response->withAddedHeader('X-Permitted-Cross-Domain-Policies', 'master-only');

            return $next($request, $response);
        });
    }

    /**
     * After initialization method and register (DI, Route, Middleware)
     */
    public function afterInitialization()
    {
        parent::afterInitialization();

        ModuleLoader::bootEasyModule(new LoggerModule());
        ModuleLoader::bootEasyModule(new SystemOptionsModule());
        ModuleLoader::bootEasyModule(new CSRFModule());
        ModuleLoader::bootEasyModule(new FlashModule());
        ModuleLoader::bootEasyModule(new AuthModule());
        ModuleLoader::bootEasyModule(new PublicModule());

        if( Session::get('auth') ){
            ModuleLoader::bootEasyModule(new AdminPanelModule());
            ModuleLoader::bootEasyModule(new CustomizerAdminPanelModule());
        }

        if (isset($this->container['settings']['protect_double_route_register']) &&
            $this->container['settings']['protect_double_route_register']
        ) {
            $this->routerControlSystem();
        }
    }

    /**
     * Register DB manager
     */
    protected function registerDB()
    {
        $capsule = new Capsule();

        $capsule->addConnection($this->container->config['db'][$this->container->settings['db_driver']]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->container['db'] = function () {
            return new Capsule();
        };
    }

    /**
     * Protected error if registered 2 identity path route
     */
    protected function routerControlSystem()
    {
        $this->container->dispatcher->addListener('app.beforeRun', function ($event) {
            \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) use ($event) {
                foreach ($event->getContainer()->get('router')->getRoutes() as $route) {
                    try {
                        $r->addRoute($route->getMethods(), $route->getPattern(), $route->getIdentifier());
                    } catch (\FastRoute\BadRouteException $e) {
                        $event->getLogger()->error('Register router: ' . $e->getMessage());
                        $event->getContainer()->get('router')->removeNamedRoute($route->getIdentifier());
                        continue;
                    }
                }
            });
        }, 1000);
    }

    public function installModule()
    {
        parent::installModule();

        $installMicroModule = new SystemOptionsModule();
        $installMicroModule->installModule();
        $installMicroModule = new AuthModule();
        $installMicroModule->installModule();
        $installMicroModule = new PublicModule();
        $installMicroModule->installModule();
        $installMicroModule = new CustomizerAdminPanelModule();
        $installMicroModule->installModule();

        $this->saveConfigForModule(self::class, ["installed"=>true, "active"=>true]);
    }

    public function uninstallModule()
    {
        parent::uninstallModule();
        $this->registerDB();

        $installMicroModule = new SystemOptionsModule();
        $installMicroModule->uninstallModule();
        $installMicroModule = new AuthModule();
        $installMicroModule->uninstallModule();
        $installMicroModule = new PublicModule();
        $installMicroModule->uninstallModule();
        $installMicroModule = new CustomizerAdminPanelModule();
        $installMicroModule->uninstallModule();

        $this->saveConfigForModule(self::class, ["installed"=>false, "active"=>false]);
    }
}