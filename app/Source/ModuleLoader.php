<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 6/11/16
 * Time: 8:52 PM
 */

namespace App\Source;

use Pimple\Container;
use Noodlehaus\Exception\ParseException;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Source\Events\BaseAppEvent;
use App\Source\Events\BaseLoggerEvent;
use App\Source\Factory\AppFactory;
use App\Source\Interfaces\IModule;
use App\Source\Interfaces\IModuleLoader;
use App\Helpers\SessionManager as Session;

class ModuleLoader implements IModuleLoader
{
    protected static $loadedModules = [];
    protected static $moduleContainer;
    protected static $coreLoaded = false;

    public static function install(IModule $module){
        self::checkDbConnection();
        $module->installModule();
    }

    public static function uninstall(IModule $module){
        self::checkDbConnection();
        $module->uninstallModule();
    }

    protected static function checkDbConnection(){
        $container = AppFactory::getInstance()->getContainer();

        if( $container->offsetExists('db') ){
            return;
        }

        $config = $container->config;
        $settings = $container->settings;

        $capsule = new Capsule();

        $capsule->addConnection($config['db'][$settings['db_driver']]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $container['db'] = function () {
            return new Capsule();
        };
    }

    public static function bootInstaller(IModule $module){
        if(!preg_match("/(installer|setup))/sui", $module->getName())){
            throw new ParseException("No load module".$module->getName()." - is don't installer module group");
        }

        self::initProcess($module);
        self::$loadedModules[$name] = $name;
    }

    public static function bootCore(IModule $module)
    {
        if(!preg_match("/core/sui", $module->getName())){
            throw new ParseException("No load module".$module->getName()." - is don't core module group");
        }

        self::initProcess($module);

        if($module->isInitModule()){
            self::$loadedModules[$name] = $name;
            self::$coreLoaded = true;
        }
    }

    public static function bootLoadModules(Container $moduleContainer)
    {
        if( !self::$coreLoaded )
            return false;

        $c = AppFactory::getInstance()->getContainer();
        $c['modules'] = self::$moduleContainer = $moduleContainer;
        $c->dispatcher->dispatch('module.modules.beforeAllInitialization');
        foreach($moduleContainer->keys() as $module){
            if( $moduleContainer[$module]->config->installed && $moduleContainer[$module]->config->active )
                self::bootModuleContainer($moduleContainer[$module]);
        }
    }

    public static function bootEasyModule(IModule $module, $name = '')
    {
        if( !$name )
            $name = $module->getName();

//        $event = new BaseAppEvent(AppFactory::getInstance(), $module);
//        AppFactory::getInstance('dispatcher')->dispatch('module.' . $name . '.beforeInitialization', $event);
//        self::initializationProcess($module, $name);
        self::initProcess($module);

        self::$loadedModules[$name] = $name;
//        $event = new BaseLoggerEvent(AppFactory::getInstance('logger'), $module);
//        AppFactory::getInstance('dispatcher')->dispatch('module.' . $name . '.afterInitialization', $event, $module);
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
                    AppFactory::getInstance('logger')->info("Module \"$name\" not installed");
                    continue;
                }
                if( !self::$moduleContainer[$name]->config->active ){
                    AppFactory::getInstance('logger')->info("Module \"$name\" not active");
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

        self::initProcess($module);
        self::$loadedModules[$name] = $name;

        $event = new BaseLoggerEvent(AppFactory::getInstance('logger'), $module);
        AppFactory::getInstance('dispatcher')->dispatch('module.' . $name . '.afterInitialization', $event, $module);
    }

    protected static function bootModuleContainer($module)
    {
        if( $module->config->only_auth && !Session::get('auth')){
            return;
        }
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

    /**
     * @param IModule $module
     */
    protected static function initProcess(IModule $module)
    {
        $module->beforeInitialization();
        $module->initialization();
        $module->registerRoute();
        $module->registerDi();
        $module->registerMiddleware();
        $module->afterInitialization();
    }
}