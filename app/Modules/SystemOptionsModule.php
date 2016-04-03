<?php

namespace App\Modules;

use Slim\App;
use App\Source\Facade\OptionsFacade;
use App\Models\Options;

class SystemOptionsModule extends AModule
{
    const MODULE_NAME = 'system_options';

    public function initialization(App $app)
    {
        parent::initialization($app);
    }

    public function registerDi()
    {
        $this->container['systemOptions'] = function ($c) {
            return new OptionsFacade(Options::where('options_group_id', 1)->get());
        };
    }
    
}
