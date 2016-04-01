<?php

namespace App\Modules;

use Slim\App;
use \Slim\Flash\Messages;

class FlashModule extends AModule
{
    const MODULE_NAME = 'session_flash';

    public function initialization(App $app)
    {
        parent::initialization($app);
    }

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
