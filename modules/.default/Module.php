<?php

namespace Modules\%system_name%;

use App\Source\AModule;

class Module extends AModule
{
    const MODULE_NAME = '%system_name%';

    public $requireModules = ['core'];

    public function installModule()
    {}

    public function uninstallModule()
    {}

    public function beforeInitialization()
    {
        parent::beforeInitialization();
    }

    public function initialization()
    {}

    public function registerDi()
    {}

    public function registerRoute()
    {}

    public function registerMiddleware()
    {}

    public function afterInitialization(){
        parent::afterInitialization();
    }
}