<?php

namespace CoreModule\Source\MicroModules;

use App\Source\AModule;
use App\Source\Facade\OptionsFacade;
use App\Models\Options;
use App\Source\RouteSystem\AdminResource;
use App\Source\RouteSystem\AdminRouteCollection;

class SystemOptionsModule extends AModule
{
    const MODULE_NAME = 'system_options';

    public function registerDi()
    {
        $this->container['systemOptions'] = function ($c) {
            return new OptionsFacade(Options::where('options_group_id', 1)->get());
        };
    }
    
    public function registerRoute()
    {
        AdminRouteCollection::add(new AdminResource('options', 'App\Controllers\Admin\OptionsController'));
        AdminRouteCollection::add(new AdminResource('group_options'));
    }
}
