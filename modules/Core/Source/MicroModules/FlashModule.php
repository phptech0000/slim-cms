<?php

namespace Modules\Core\Source\MicroModules;

use Slim\Flash\Messages;
use App\Source\AModule;

class FlashModule extends AModule
{
    const MODULE_NAME = 'session_flash';

    public function registerDi()
    {
    	$this->container['flash'] = function () {
            return new Messages();
        };

        $flash = $this->container->flash;

        $this->container['flashMess'] = function () use ($flash) {
            return $flash->getMessages();
        };
    }
    
}
