<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 6/11/16
 * Time: 4:10 PM
 */
namespace App\Source\Interfaces;

interface IModuleManager
{
    public function init();

    public function getModules();

    public function getModuleByName($moduleName);

    public function registerModules();
}