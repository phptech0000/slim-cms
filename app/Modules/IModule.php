<?php

namespace App\Modules;

interface IModule
{
    public function checkRequireModule(array $arModulesName = ['core']);
    public function installModule();
    public function uninstallModule();

    public function initialization($app);
    public function registerRoute();
    public function registerDi();
    public function registerMiddleware();
    public function afterInitialization();

    public static function isInitModule();
    public static function getName();
}
