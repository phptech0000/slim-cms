<?php

namespace Modules\Core\Source\MicroModules;

use App\Source\AModule;
use Modules\Core\Source\Libs\Middleware\ItemPerPageMiddleware;
use Modules\Core\Source\Libs\Middleware\LastPagePaginatorMiddleware;

class CustomizerAdminPanelModule extends AModule
{
    const MODULE_NAME = 'customizer_admin_panel';

    public function registerRoute()
    {
    	$this->app->options('/ajax', 'App\Controllers\Admin\UniversalAjaxController:update')->add('Modules\Core\Source\Libs\Middleware\CheckAjaxMiddleware')->setName('ajax.custom.field');
    }

    public function registerMiddleware()
    {
        $this->app->add(new LastPagePaginatorMiddleware($this->container));
    	$this->app->add(new ItemPerPageMiddleware($this->container));
    }

    public function afterInitialization()
    {
        parent::afterInitialization();

        $this->container->dispatcher->addListener('middleware.itemparpage.after', function ($event) {
            $page = new LastPagePaginatorMiddleware($event->getContainer());
            $page->setOption(1, $event->getParams()['allParams']);
        });
    }
}
