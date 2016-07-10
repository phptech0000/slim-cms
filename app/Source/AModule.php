<?php

namespace App\Source;

use App\Source\Factory\AppFactory;
use App\Source\Interfaces\IModule;
use Illuminate\Support\Str;

abstract class AModule implements IModule
{
	protected $container;
    protected $app;

    public $info;
//    protected static $loaded = false;
    protected $loaded = false;
    protected $specialData;

    public $requireModules = ['Core'];

    public function __construct($data = null) {
    	$c = get_called_class();
        if (!$c::MODULE_NAME)
        {
            throw new \Exception('Constant MODULE_NAME is not defined on subclass ' . get_class($c));
        }
        $this->specialData = $data;
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
        //static::$loaded = true;
        $this->loaded = true;
    }

    public function isInitModule()
    {
        //return (bool) static::$loaded;//$this->loaded;
        return (bool) $this->loaded;
    }

    public static function getName()
    {
        return static::MODULE_NAME;
    }

    public function installModule()
    {
        $this->beforeInitialization();
    }

    public function uninstallModule()
    {
        $this->beforeInitialization();
    }

    public function registerRoute()
    {}

    public function registerMiddleware()
    {}

    public function registerDi()
    {}

    protected function saveConfigForModule($class, array $arData){
        $file = MODULE_PATH.Str::ucfirst($class::MODULE_NAME)."/config.json";
        $arConfigData = new \stdClass();
        if(file_exists($file)){
            $arConfigData = json_decode(file_get_contents($file));
        }
        foreach($arData as $key=>$item){
            $key = strtolower($key);
            $arConfigData->$key = $item;
        }

        file_put_contents($file, json_encode($arConfigData, JSON_PRETTY_PRINT));
    }
}