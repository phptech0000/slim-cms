<?php

namespace App\Source;

use Slim\App;

/**
 * Base interface for all modules
 * Interface IModule
 * @package App\Modules
 */
interface IModule
{
    /**
     * Run if module no install
     * @return void
     */
    public function installModule();

    /**
     * Run if remove module from system
     * @return void
     */
    public function uninstallModule();

    /**
     * Instance app, and container in module
     * @param App $app
     * @return void
     */
    public function beforeInitialization(App $app);

    /**
     * Run if installed module(every loading system)
     * @return void
     */
    public function initialization();

    /**
     * Register route in slim framework
     * @return void
     */
    public function registerRoute();

    /**
     * Register DI container in slim framework
     * @return void
     */
    public function registerDi();

    /**
     * Register Middleware in slim framework
     * @return void
     */
    public function registerMiddleware();

    /**
     * Run after init module and register methods
     * @return void
     */
    public function afterInitialization();

    /**
     * Return loaded module status
     * @return bool
     */
    public function isInitModule();

    /**
     * Return module name
     * @return string
     */
    public static function getName();
}
