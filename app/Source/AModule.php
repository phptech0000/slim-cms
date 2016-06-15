<?php

namespace App\Source;

use App\Source\Factory\AppFactory;
use App\Source\Interfaces\IModule;

abstract class AModule implements IModule
{
	protected $container;
    protected $app;

    public $info;
    protected $loaded = false;

    public $requireModules = ['Core'];

    public function __construct() {
    	$c = get_called_class();
        if (!$c::MODULE_NAME)
        {
            throw new \Exception('Constant MODULE_NAME is not defined on subclass ' . get_class($c));
        }
    }

    public function beforeInitialization()
    {
        $this->app = AppFactory::getInstance();
        $this->container = $this->app->getContainer();
    }

    public function initialization()
    {}

    public function afterInitialization()
    {
        $this->loaded = true;
    }

    public function isInitModule()
    {
        return (bool) $this->loaded;
    }

    public static function getName()
    {
        return static::MODULE_NAME;
    }

    public function installModule()
    {}

    public function uninstallModule()
    {}

    public function registerRoute()
    {}

    public function registerMiddleware()
    {}

    public function registerDi()
    {}
}