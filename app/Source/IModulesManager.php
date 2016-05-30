<?php

namespace App\Source;

interface IModulesManager
{
    public static function getInstance($container='', $app='');

    public function registerModule(IModule $module);
    public function install($name);
    public function uninstall($name);

    public function boot();

    public function getModules();
    public function getModule($name);
    /**
     * Method check loaded module
     * @param array $arModulesName
     * @return void
     */
    public function checkRequireModule(array $arModulesName = []);
}
