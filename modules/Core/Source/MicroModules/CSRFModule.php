<?php

namespace CoreModule\Source\MicroModules;

use App\Source\AModule;
use Slim\Csrf\Guard;
use App\Middleware\CSRFMiddleware;

class CSRFModule extends AModule
{
    const MODULE_NAME = 'csrf';

    public function registerDi()
    {
    	$this->container['csrf'] = function ($c) {
		    return new Guard;
		};
    }

    public function registerMiddleware()
    {
    	$this->app->add(new CSRFMiddleware());
    }
}
