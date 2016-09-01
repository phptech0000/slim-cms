<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 6/11/16
 * Time: 12:31 PM
 */

namespace App\Source;


use App\Helpers\FileWorker;
use App\Source\Factory\AppFactory;
use App\Source\Interfaces\IModuleManager;
use Pimple\Container;

class ModuleManager implements IModuleManager
{
    public static $disableFolders = ['.', '..', '.default', 'Core'];
    protected static $moduleContainer;
    protected $modules;
    protected $modulesDir;

    public $cacheFolder = 'modules';
    protected $cacheFileName;
    protected $cacheFullPath;

    /**
     * ModuleLoader constructor.
     * @param $modulesDir
     */
    public function __construct($modulesDir)
    {
        if (!is_object(self::$moduleContainer)) {
            self::$moduleContainer = new Container();
        }

        $this->cacheFileName = md5($modulesDir).'.php';
        $this->modulesDir = $modulesDir;
    }

    public function init($recreateCache = false)
    {
        if( $this->checkCache() && !$recreateCache ){
            $this->getCache();
        } else {
            $this->modules = $this->findModules($this->modulesDir);
            $this->checkModules($this->modules);
            $this->setCache();
        }
        return $this;
    }

    protected function findModules($dirName)
    {
        $folders = array_values(array_diff(scandir($dirName), self::$disableFolders));
        return $folders;
    }

    protected function checkModules(array $modules)
    {
        foreach ($modules as $module) {
            $path = $this->modulesDir . $module . '/';
            if (is_file($path . "info.json")) {
                $this->checkModuleInfo($path . "info.json", $module);
            } else {
                AppFactory::getInstance('logger')->error("Don't find info.json for module \"$module\"");
            }
            if (is_file($path . "config.json")) {
                $this->checkModuleConfig($path . "config.json", $module);
            } else {
                AppFactory::getInstance('logger')->error("Don't find config.json for module \"$module\"");
            }
        }
    }

    protected function checkModuleInfo($json, $moduleName)
    {
        $moduleInfo = FileWorker::getJsonDataFile($json);
        if ($moduleName == $moduleInfo->system_name) {
            $this->addModuleToContainer($moduleInfo);
        } else {
            AppFactory::getInstance('logger')->error("info.json is bad for module \"$module\". Please check format file.");
        }
    }

    protected function checkModuleConfig($json, $moduleName)
    {
        $moduleInfo = FileWorker::getJsonDataFile($json);

        if (isset($moduleInfo->installed) &&
            isset($moduleInfo->active)
        ) {
            $this->addModuleConfig($moduleName, $moduleInfo);
        } else {
            AppFactory::getInstance('logger')->error("config.json is bad for module \"$module\". Please check format file.");
        }
    }

    protected function addModuleToContainer($moduleInfo)
    {
        self::$moduleContainer[$moduleInfo->system_name] = $moduleInfo;
    }

    protected function addModuleConfig($module, $moduleInfo)
    {
        if( isset(self::$moduleContainer[$module]) )
            self::$moduleContainer[$module]->config = $moduleInfo;
    }

    public function getModules()
    {
        return self::$moduleContainer;
    }

    public function getModuleByName($moduleName)
    {
        return self::$moduleContainer[$moduleName];
    }

    public function registerModules()
    {
        $arModules = self::$moduleContainer->keys();
        foreach ($arModules as $moduleName) {
            $moduleInfo = self::$moduleContainer[$moduleName];

            if ($moduleInfo->module)
                continue;

            if ($moduleInfo->config->installed &&
                $moduleInfo->config->active
            ) {
                $moduleInfo->module = new Container();
                $moduleInfo->module["init"] = function () use ($moduleInfo) {
                    $cl = 'Modules\\' . $moduleInfo->system_name . '\\Module';
                    if ($moduleInfo->config->load)
                        $cl = $moduleInfo->config->load;

                    $module = new $cl();

                    $module->setInfo(clone($moduleInfo));
                    return $module;
                };
                if ($moduleInfo->config->decorators) {
                    foreach ($moduleInfo->config->decorators as $decorator) {
                        $moduleInfo->module->extend("init", function ($module, $container) use ($decorator) {
                            return new $decorator($module);
                        });
                    }
                }
            }
        } // endforeach
    }

    /**
     * @param bool|false $reCreate
     */
    protected function checkCache()
    {
        $this->cacheFullPath = CACHE_PATH.$this->cacheFolder.'/'.$this->cacheFileName;

        if( is_file($this->cacheFullPath) ){
            return true;
        }

        return false;
    }

    protected function setCache(){
        if( !is_dir(CACHE_PATH.$this->cacheFolder) && !mkdir(CACHE_PATH.$this->cacheFolder)){
            throw new \Exception("ModuleManager - can not create folder cache");
        }

        $arModules = self::$moduleContainer->keys();
        $arItems = [];
        foreach ($arModules as $moduleName) {
            $moduleInfo = self::$moduleContainer[$moduleName];
            $arItems[] = $moduleInfo;
        }

        FileWorker::saveJsonFile($this->cacheFullPath, $arItems);
    }

    protected function getCache(){
        $arItems = FileWorker::getJsonDataFile($this->cacheFullPath);
        foreach ($arItems as $moduleInfo) {
            $this->addModuleToContainer($moduleInfo);
        }
    }
}