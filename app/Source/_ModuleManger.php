<?php

namespace App\Source;

use Composer\Autoload\ClassLoader;
use Illuminate\Support\Str;
use Slim\App;

class ModuleManager implements Interfaces\IModulesManager
{

    protected $app;
    protected $container;
    protected $classLoader;

    protected static $instance;
    protected static $modulesInfo = [];

    protected function __construct(App $app = '', ClassLoader $classLoader = '')
    {
        $this->app = $app;
        $this->container = $app->getContainer();
        $this->classLoader = $classLoader;
    }

    protected function __clone()
    {
    }

    /**
     * Singleton - set new instance and get instance
     * @param App $app
     * @param ClassLoader $classLoader
     *
     * @return IModulesManager
     */
    public static function getInstance(App $app = '', ClassLoader $classLoader = '')
    {
        if (!self::$instance) {
            self::$instance = new self($app, $classLoader);
        }

        return self::$instance;
    }

    public function registerModule(IModule $module)
    {
        if ($module->isInitModule()) {
            $this->registerModuleByName($module->getName());
            self::$moduleLoaded[Str::ucfirst($module->getName())];
            return;
        }

        $arOptions = [
            "realName" => $module->getName(),
            "registerName" => $module->getName(),
            "module" => $module,
            "isLoaded" => $module->isInitModule(),
        ];

        $this->setModuleInfo($moduleName, $arOptions);
    }

    public function registerModuleByName($moduleName)
    {
        $this->setModuleInfo($moduleName);
    }

    public function registerModules(array $modules = [])
    {
        foreach ($modules as $module) {
            $this->registerModule($module);
        }
    }

    public function registerModulesByName(array $modulesNames = [])
    {
        foreach ($modules as $name) {
            $this->registerModuleByName($name);
        }
    }

    public function loadCoreModule(IModule $module)
    {
        // TODO: Implement loadCoreModule() method.
    }

    public function loadModuleByName($moduleName)
    {
        // TODO: Implement loadModuleByName() method.
    }

    public function loadModule(IModule $module)
    {
        // TODO: Implement loadModule() method.
    }

    public function loadAllRegisterModules($moduleName)
    {
        // TODO: Implement loadAllRegisterModules() method.
    }

    public function setExtModule($moduleName, IModule $module)
    {
        $arModInfo = $this->getModuleByName($moduleName);
        $arModInfo['extClass'] = $module;
    }

    public function getExtModule($moduleName)
    {
        $arModInfo = $this->getModuleByName($moduleName);
        return ($arModInfo["extClass"]);
    }

    public function getRegisterModules()
    {
        return array_keys(self::$modulesInfo);
    }

    public function getLoadedModules()
    {
        return $this->findModuleBy("isLoaded", true);
    }

    public function getAllLoadedModuleClassName()
    {
        // TODO: Implement getAllLoadedModuleClassName() method.
    }

    public function getModuleByName($name)
    {
        return $this->findModuleBy("registerName", $name);
    }

    /**
     * Method check loaded module
     * @param array $arModulesName
     * @return void
     */
    public function checkRequireModule(array $modulesNames = [])
    {
        foreach($modulesNames as $name){
            $arModuleInfo = $this->getModuleByName($name);
            if( !$arModuleInfo['isLoaded'] ){
                // todo load module
            }
        }
    }

    /**
     * Set or create new module collector item
     *
     * @param $name - name register name
     * @param array $options - options(show getDefaultModuleInfo)
     */
    protected function setModuleInfo($name, array $options = [])
    {
        $options["registerName"] = $name;
        $options["formatName"] = Str::ucfirst($name);
        $arInfo = $this->getDefaultModuleInfo($options);

        self::$modulesInfo[$arInfo["formatName"]] = $arInfo;
    }

    /**
     * Make default module info for save
     *
     * @param array $options - (formatName|realName|registerName|extClass|module|isLoaded)
     * @return array
     */
    protected function getDefaultModuleInfo(array $options = [])
    {
        return array_merge([
            "formatName" => "",
            "realName" => "",
            "registerName" => "",
            "extClass" = "",
            "module" => "",
            "isLoaded" => false,
        ], $options);
    }

    /**
     * Find module by params
     *
     * @param string $param - parametr for find item
     * @param mixed $value - value equals
     * @return array
     */
    protected function findModuleBy($param, $value)
    {
        return array_filter(self::$modulesInfo, function($item) use ($param, $value){
            if( $value == 'notNull' )
                return ( isset($item[$param]) && $item[$param] != "" )

            return ( isset($item[$param]) && $item[$param] == $value )
        });
    }
}