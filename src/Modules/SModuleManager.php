<?php

namespace SlimCMS\Modules;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

/**
* 
*/
class SModuleManager
{
	public $moduleNamespace = "\\Modules";
	protected $path = MODULE_PATH;
    protected $cache;
    protected $modulesName = [];

	protected $filesystem;
	protected $moduleContainer;
	
	public function __construct($cache)
	{
		$this->filesystem = new Filesystem();
		$this->moduleContainer = new Container();
	    $this->cache = $cache;
	}

	public function loadModules(){
		$folders = $this->filesystem->directories($this->path);
		foreach ($folders as $folder) {
			$this->initModule($folder);
		}
	}

	public function module($name){
		return $this->moduleContainer->make($name);
	}

	public function getModules(){
	    return $this->moduleContainer;
    }

	protected function initModule($folder){
		$config = $this->checkConfig($this->extModuleInfo($folder.DIRECTORY_SEPARATOR.'config.json'));
		$moduleName = $this->filesystem->name($folder);
		$info = $this->checkInfo($this->extModuleInfo($folder.DIRECTORY_SEPARATOR.'info.json'), $moduleName);
		$this->loadModule($moduleName, $config, $info);
	}

	protected function extModuleInfo($path){
		if($this->filesystem->exists($path)){
			return json_decode($this->filesystem->get($path));
		}
	}

	protected function loadModule($name, $config, $info){
		$ext = false;
		$baseClass = $this->moduleNamespace.'\\'.$name.'\\Module';
		$cl = $baseClass;

		if( !$config->params->installed ||
			!$config->params->active ||
			($config->params->only_admin && false)
		){
			return false;
		}

		if( $this->moduleContainer->offsetExists($this->moduleNamespace.'\\'.$name) )
			return false;

		if( isset($config->class_ext) ){
			$baseClass = $cl;
			$cl = $config->class_ext;
			$ext = true;
		}

		if( !class_exists($cl) )
			throw new \Exception("Class \"$cl\" not found", 1);

		if( $ext ){
			$p = trim(get_parent_class($cl), '\\');
			$b = trim($baseClass, '\\');
			if( $p != $b )
				throw new \Exception("Class \"$cl\" not extend base class \"$baseClass\"", 1);			
		}

		if( isset($config->dependeny) && is_array($config->dependeny) ){
			$this->checkDependecies($config->dependeny);
		}

		$this->moduleContainer->singleton($this->moduleNamespace.'\\'.$name, function() use ($info, $config, $cl) {
			$module = new $cl();
			foreach ($info as $key => $value) {
				$module->$key = $value;
			}
			$module->config = $config->params;
			foreach ($config->params as $key => $value) {
				$module->$key = $value;
			}
			return $module;
		});
		$this->moduleContainer->alias($this->moduleNamespace.'\\'.$name, $info->system_name);
        $this->modulesName[] = $info->system_name;

		if( isset($config->class_decorators) && is_array($config->class_decorators) ){
			$this->decoratorsInit($this->moduleNamespace.'\\'.$name, $config->class_decorators);
		}
	}

    public function keys(){
        return $this->modulesName;
    }

	protected function checkConfig($config){
		$defClassConfig = ["installed", "active", "only_admin"];

		if( !isset($config->params) )
			$config->params = new \stdClass();

		foreach ($defClassConfig as $type) {
			if( !isset($config->params->$type) ){
				$config->params->$type = false;
			}
		}

		return $config;
	}

	protected function checkInfo($info, $name){
		if( !($info instanceof \stdClass) ){
			$info = new \stdClass();
		}

		if( !isset($info->system_name) )
			$info->system_name = $name;

		return $info;
	}

	protected function decoratorsInit($name, array $decorators){
		foreach ($decorators as $decorClass) {
			if( !class_exists($decorClass) )
				throw new \Exception("Class decorator \"$decorClass\" - not found", 1);

			$this->moduleContainer->extend($name, function($module) use ($decorClass){
				return new $decorClass($module);
			});
		}
	}

	protected function checkDependecies(array $dependeny){
		foreach ($dependeny as $moduleName) {
			if( !$this->moduleContainer->offsetExists($this->moduleNamespace.'\\'.$moduleName) ){
				$folder = $this->path.DIRECTORY_SEPARATOR.$moduleName;
				if( !$this->filesystem->isDirectory($folder) )
					throw new \Exception("Module \"$moduleName\" - not found", 1);
				
				$this->initModule($folder);

				if(!$this->moduleContainer->offsetExists($this->moduleNamespace.'\\'.$moduleName)){
					throw new \Exception("Module \"$moduleName\" - don't loaded. Please сheck whether the module is installed and enabled", 1);
				}
			}
		}
	}
}