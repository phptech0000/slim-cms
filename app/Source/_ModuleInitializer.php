<?php

namespace App\Source;

use App\Source\Events\BaseAppEvent;
use App\Source\Events\BaseLoggerEvent;
use Composer\Autoload\ClassLoader;
use Illuminate\Support\Str;

/**
 * This will load modules
 */
class ModuleInitializer
{
    /**
     * @var array
     */
    protected static $classNameLoaded = [];

    protected static $instance;

    /**
     * @var Slim
     */
    protected $app;

    /**
     * @var Slim\Container
     */
    protected $container;

    /**
     * @var ClassLoader
     */
    protected $classLoader;

    /**
     * @var array
     */
    protected static $moduleContainer = [];

    /**
     * @var array
     */
    protected static $moduleNames = [];

    /**
     * @param $app
     * @param ClassLoader $classLoader
     * @param array $settings
     */
    public function __construct($app, ClassLoader $classLoader)
    {
        $this->app = $app;
        $this->container = $app->getContainer();

        $this->classLoader = $classLoader;
    }

    public static function getInstance($app='', $classLoader='')
    {
        if (!self::$instance) {
            self::$instance = new self($app, $classLoader);
        }

        return self::$instance;
    }

    /**
     * @param array $arModules
     */
    public function registerModuleNames(array $arModules = [])
    {
        $arModules = array_map(function ($v) {
            return Str::ucfirst($v);
        }, $arModules);
        self::$moduleNames = array_unique(array_merge(self::$moduleNames, $arModules));
    }

    /**
     * @param $module
     */
    public function registerModule(IModule $module, $regInContainer = true)
    {
        if($module->isInitModule())
            return;

        $className = get_class($module);
        //p($className);//(Ext[\w]+){0,1}[Module\\\]+$
        preg_match("/(\w+)Module($|\\\Module)/s", $className, $t);
        $name = Str::ucfirst($t[1]);
        $_name = Str::ucfirst($module->getName());
        if( $name != $_name ){
            if(isset($this->container->logger)){
                $this->container->get('logger')->error('Module "'. $name .'" no use real name, masqed "'. $_name .'". I use name: '.$_name);
            }
        }

        self::$moduleContainer[$_name] = $module;
        self::$classNameLoaded[] = $className;

        if ($regInContainer)
            $this->registerModuleNames([$_name]);
    }

    public function initProcess(){
        foreach(self::$moduleContainer as $module){
            $module->beforeInitialization($this->app);
            $module->initialization();
            //$this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.route', $event);
            $module->registerRoute();
            //$this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.route', $event);
            //$this->container->dispatcher->dispatch('module.'.$name.'.beforeRegister.di', $event);
            $module->registerDi();
            //$this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.di', $event);
            //$this->container->dispatcher->dispatch('module.'.$name.'.beforeRegister.middleware', $event);
            $module->registerMiddleware();
            //$this->container->dispatcher->dispatch('module.'.$name.'.afterRegister.middleware', $event);
            $module->afterInitialization();
        }
    }

    public function bootCore()
    {
        $module = $this->getModuleByName('Core');

        if (!$module) {
            throw new \RuntimeException('Core module not found.');
        }

        if ($module->isInitModule()) {
            return;
        }

        $this->container['modules'] = $this->getModuleNames();

        $this->initializationProcess($module);

        $event = new BaseAppEvent($this->app);
        $this->container->dispatcher->dispatch('module.core.afterInitialization', $event);

        return $this;
    }

    public function boot()
    {
        foreach ($this->getModules() as $module) {
            if ($module->isInitModule()) {
                continue;
            }
            $name = $module->getName();

            if( !$this->checkRequireModule($module->requireModules) ){
                if( ($logger = $this->container->get('logger')) )
                    $logger->error("Module $name no loaded require module not found.");

                continue;
            }

            $event = new BaseAppEvent($this->app, $module);

            $this->container->dispatcher->dispatch('module.' . $name . '.beforeInitialization', $event);

            $this->initializationProcess($module);

            $event = new BaseLoggerEvent($this->container->logger, $module);
            $this->container->dispatcher->dispatch('module.' . $name . '.afterInitialization', $event, $module);
        }

        $this->container->dispatcher->dispatch('module.allModuleLoaded');
    }

    /**
     * @return array
     */
    public static function getModuleNames()
    {
        return self::$moduleNames;
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function getModuleByName($name)
    {
        return self::$moduleContainer[Str::ucfirst($name)];
    }

    /**
     * @return array
     */
    public static function getAllLoadedModuleClassName(){
        return self::$classNameLoaded;
    }

    /**
     * Load the module. This will run for all modules, use for routes mainly
     * @param string $moduleName Module name
     */
    public function initModules()
    {
        foreach (self::getModuleNames() as $moduleName) {
            // e.g. "/path/to/modules/{module_name}/Module.php"
            $moduleName = Str::ucfirst($moduleName);
            $moduleClassPath = MODULE_PATH . $moduleName . "/Module.php";
            $pathInfo = pathinfo($moduleClassPath);
            if (!file_exists($moduleClassPath)) {
                if(isset($this->container->logger)){
                    $this->container->get('logger')->error('Module class file '. $moduleClassPath .', could not found');
                }
                continue;
            }
            $className = $moduleName . "Module";
            $this->classLoader->addPsr4($className . "\\", $pathInfo['dirname'] . '/', 1);
            $module = "\\$className\\Module";

            $this->registerModule(new $module());
        }

        // next, load settings of all modules
        /*foreach ($moduleClassMap as $moduleName => $moduleClassName) {
            $moduleSettings = $moduleClassName::getModuleConfig();
            $allSettings = $container['settings']->all();
            if (!isset($allSettings['modules'][$moduleName]) or !is_array($allSettings['modules'][$moduleName])) {
                $allSettings['modules'][$moduleName] = [];
            }
            $allSettings['modules'][$moduleName] = array_merge_recursive($allSettings['modules'][$moduleName], $moduleSettings);
            $container['settings']->__construct($allSettings);
        }*/
    }

    public function getModules()
    {
        return self::$moduleContainer;
    }

    public function initializationProcess($module)
    {
        $module->beforeInitialization($this->app);
        $module->initialization();
        $module->registerRoute();
        $module->registerDi();
        $module->registerMiddleware();
        $module->afterInitialization();
    }

    public function checkRequireModule(array $arModulesName = [])
    {
        $arAllInitModules = $this->getModuleNames();
        foreach($arModulesName as $moduleName){
            if( !in_array(Str::ucfirst($moduleName), $arAllInitModules) ){
                $this->container->get('logger')->error("Module $module from required - not found.");
                return false;
            }

            $module = $this->getModuleByName($moduleName);

            if( !$module->isInitModule() ){
                $this->initializationProcess($module);
            }
        }

        return true;
    }
}