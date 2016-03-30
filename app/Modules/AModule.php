<?php

namespace App\Modules;

use Slim\App;
use App\Helpers\Enforcer;

abstract class AModule implements IModule
{
	protected $container;
    protected $app;

    protected static $loaded = false;

    public function __construct() {
    	$c = get_called_class();
        if (!$c::MODULE_NAME)
        {
            throw new \Exception('Constant MODULE_NAME is not defined on subclass ' . get_class($c));
        }
    }

    public function initialization(App $app)
    {
        $this->container = $app->getContainer();
        $this->app = $app;
    }

    public function afterInitialization()
    {
        self::$loaded = true;
    }

    public static function isInitModule()
    {
        return (bool) self::$loaded;
    }

    public static function getName()
    {
        return static::MODULE_NAME;
    }
}