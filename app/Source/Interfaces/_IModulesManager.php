<?php

namespace App\Source\Interfaces;

use App\Source\IModule;
use Slim\App;
use Composer\Autoload\ClassLoader;

interface IModulesManager
{
    /**
     * Singleton - set new instance and get instance
     * @param App $app
     * @param ClassLoader $classLoader
     *
     * @return IModulesManager
     */
    public static function getInstance(App $app='', ClassLoader $classLoader='');

    public function registerModule(IModule $module);
    public function registerModuleByName($moduleName);
    public function registerModules(array $modules = []);
    public function registerModulesByName(array $modulesNames = []);

    public function loadCoreModule(IModule $module);
    public function loadModuleByName($moduleName);
    public function loadModule(IModule $module);
    public function loadAllRegisterModules($moduleName);

    public function setExtModule($moduleName, IModule $module);
    public function getExtModule($moduleName);

    public function getRegisterModules();
    public function getLoadedModules();
    public function getAllLoadedModuleClassName();

    public function getModuleByName($name);

    /**
     * Method check loaded module
     * @param array $arModulesName
     * @return void
     */
    public function checkRequireModule(array $modulesNames = []);
}
