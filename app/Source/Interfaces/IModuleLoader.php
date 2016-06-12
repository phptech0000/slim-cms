<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 6/11/16
 * Time: 8:55 PM
 */
namespace App\Source\Interfaces;

use Pimple\Container;

interface IModuleLoader
{
    public static function bootCore(IModule $module);

    public static function bootLoadModules(Container $moduleContainer);

    public static function bootEasyModule(IModule $module);
}