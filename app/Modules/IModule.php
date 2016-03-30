<?php

namespace App\Modules;

use Slim\App;

interface IModule
{
    public function checkRequireModule(array $arModulesName = ['core']);
    public function installModule();
    public function uninstallModule();

    public function initialization(App $app);
    public function registerRoute();
    public function registerDi();
    public function registerMiddleware();
    public function afterInitialization();

    public static function isInitModule();
    public static function getName();
}
