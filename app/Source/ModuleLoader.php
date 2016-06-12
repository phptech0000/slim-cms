<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 6/11/16
 * Time: 8:52 PM
 */

namespace App\Source;


use App\Source\Events\BaseAppEvent;
use App\Source\Events\BaseLoggerEvent;
use App\Source\Factory\AppFactory;
use App\Source\Interfaces\IModule;
use App\Source\Interfaces\IModuleLoader;
use Noodlehaus\Exception\ParseException;
use Pimple\Container;

class ModuleLoader implements IModuleLoader
{
    protected static $loadedModules = [];
    protected static $moduleContainer;

    public static function bootCore(IModule $module)
    {
        if(!preg_match("/core/sui", $module->getName())){
            throw new ParseException("No load module".$module->getName()." - is don't core module group");
        }

        $module->beforeInitialization();
        $module->initialization();
        $module->registerRoute();
        $module->registerDi();
        $module->registerMiddleware();
        $module->afterInitialization();

        self::$loadedModules[$name] = $name;
    }

    public static function bootLoadModules(Container $moduleContainer)
    {
        $c = AppFactory::getInstance()->getContainer();
        $c['modules'] = self::$moduleContainer = $moduleContainer;
        $c->dispatcher->dispatch('module.modules.beforeAllInitialization');
        foreach($moduleContainer->keys() as $module){
            self::bootModuleContainer($moduleContainer[$module]);
        }
    }

    public static function bootEasyModule(IModule $module, $name = '')
    {
        if( !$name )
            $name = $module->getName();

        $event = new BaseAppEvent(AppFactory::getInstance(), $module);
        AppFactory::getInstance('dispatcher')->dispatch('module.' . $name . '.beforeInitialization', $event);
        self::initializationProcess($module, $name);
        $event = new BaseLoggerEvent(AppFactory::getInstance('logger'), $module);
        AppFactory::getInstance('dispatcher')->dispatch('module.' . $name . '.afterInitialization', $event, $module);
    }

    protected static function checkDependency($arDependency=false)
    {
        if(!$arDependency || !is_array($arDependency))
            return;
        foreach ($arDependency as $name) {
            if(self::$loadedModules[$name])
                continue;

            if(self::$moduleContainer[$name]){
                if( !self::$moduleContainer[$name]->config->installed ){
                    AppFactory::getInstance('logger')->info("Module \"$module\" not installed");
                    continue;
                }
                if( self::$moduleContainer[$name]->config->active ){
                    AppFactory::getInstance('logger')->info("Module \"$module\" not active");
                    continue;
                }
                self::bootModuleContainer(self::$moduleContainer[$name]);
            }
            else
                AppFactory::getInstance('logger')->error("Can't find module \"$module\" in container");
        }
    }

    protected static function initializationProcess(IModule $module, $name)
    {
        $event = new BaseAppEvent(AppFactory::getInstance(), $module);
        AppFactory::getInstance('dispatcher')->dispatch('module.' . $name . '.beforeInitialization', $event);

        $module->beforeInitialization();
        $module->initialization();
        $module->registerRoute();
        $module->registerDi();
        $module->registerMiddleware();
        $module->afterInitialization();

        self::$loadedModules[$name] = $name;
        $event = new BaseLoggerEvent(AppFactory::getInstance('logger'), $module);
        AppFactory::getInstance('dispatcher')->dispatch('module.' . $name . '.afterInitialization', $event, $module);
    }

    protected static function bootModuleContainer($module)
    {
        if( $module->module === null ){
            AppFactory::getInstance('logger')->error("Can't find module class for \"{$module->system_name}\"");
            return;
        }
        if( $module->module['init']->isInitModule()){
            self::$loadedModules[$module->system_name] = $module->system_name;
            return;
        }

        self::checkDependency($module->config->dependeny);

        self::initializationProcess($module->module['init'], $module->system_name);
    }
}