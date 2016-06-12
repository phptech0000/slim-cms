<?php

namespace Modules\ModuleGenerator;

use App\Source\AModule;
use App\Source\Composite\Menu;
use App\Helpers\SessionManager;

class Module extends AModule
{
    const MODULE_NAME = 'ModuleGenerator';

    public $requireModules = ['Core'];

    public function installModule()
    {}

    public function uninstallModule()
    {}

    public function beforeInitialization()
    {
        parent::beforeInitialization();
    }

    public function initialization()
    {
        $item = new Menu('Generator new module', [
            'menu_name' => 'developers.generator_module',
            'url' => '/admin/generate_module',
            'link_attr' => [
                'icon' => 'fa fa-ban fa-fw'
            ],
            'meta_attr' => [
                'onlyDevelopersMode' => true,
            ],
        ]);
        $this->container->get('adminMenuLeft')->getByName('section.only_developers')->add($item);
    }

    public function registerRoute()
    {
        $this->adminPanelRouteRegister();
    }

    public function afterInitialization(){
        parent::afterInitialization();
    }

    protected function adminPanelRouteRegister(){
        if( SessionManager::has('auth') && SessionManager::get('auth') && $this->container->systemOptions->isDevMode()){
            $this->app->get('/admin/generate_module', 'App\Controllers\Admin\ModuleGenerator:index')->setName('developers.module.generator');
            $this->app->post('/admin/generate_module', 'App\Controllers\Admin\ModuleGenerator:doAdd')->setName('developers.module.generator.add');
        }
    }
}