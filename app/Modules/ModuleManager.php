<?php

namespace App\Modules;

class ModuleManager implements IModulesManager
{
    /**
     * @var array
     */
    protected static $moduleContainer = [];

    /**
     * @var mixed
     */
    protected $app;

    /**
     * @var mixed
     */
    protected $container;

    /**
     * @param $container
     * @param $app
     */
    public function __construct($container, $app)
    {
        $this->app = $app;
        $this->container = $container;
    }

    /**
     * @param $module
     */
    public function registerModule(IModule $module)
    {
        self::$moduleContainer[$module->getName()] = $module;
    }

    /**
     * @param $name
     */
    public function install($name)
    {}

    /**
     * @param $name
     */
    public function uninstall($name)
    {}

    public function coreInit()
    {
        $module = $this->getModule('core');

        if (!$module) {
            throw new RuntimeException('Core module not found.');
        }

        if ($module->isInitModule()) {
            return;
        }

        $this->container['modules'] = $this->getModulesName();

        $module->initialization($this->app);

        $this->container->dispatcher->dispatch('module.core.beforeRegister.route');
        $module->registerRoute();
        $this->container->dispatcher->dispatch('module.core.afterRegister.route');
        $this->container->dispatcher->dispatch('module.core.beforeRegister.di');
        $module->registerDi();
        $this->container->dispatcher->dispatch('module.core.afterRegister.di');
        $this->container->dispatcher->dispatch('module.core.beforeRegister.middleware');
        $module->registerMiddleware();
        $this->container->dispatcher->dispatch('module.core.afterRegister.middleware');
        $module->afterInitialization();
        $this->container->dispatcher->dispatch('module.core.afterInitialization');

        return $this;
    }

    public function boot()
    {
        foreach ($this->getModules() as $module) {
            if ($module->isInitModule()) {
                continue;
            }
            $name = $module->getName();

            $this->container->dispatcher->dispatch('module.'.$name.'.beforeInitialization');
            $module->initialization($this->app);
            $this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.route');
            $module->registerRoute();
            $this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.route');
            $this->container->dispatcher->dispatch('module.'.$name.'.beforeRegister.di');
            $module->registerDi();
            $this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.di');
            $this->container->dispatcher->dispatch('module.'.$name.'.beforeRegister.middleware');
            $module->registerMiddleware();
            $this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.middleware');
            $module->afterInitialization();
            $this->container->dispatcher->dispatch('module.'.$name.'.afterInitialization');
        }

        $this->container->dispatcher->dispatch('module.allModuleLoaded');
    }

    public function getModules()
    {
        return self::$moduleContainer;
    }

    protected function getModulesName()
    {
        $arModules = [];
        foreach ($this->getModules() as $module) {
            $arModules[] = $module->getName();
        }
        return $arModules;
    }

    /**
     * @param $name
     */
    public function getModule($name)
    {
        return self::$moduleContainer[$name];
    }
}
