<?php

namespace CustomizeradminpanelModule;

use App\Middleware\ItemPerPageMiddleware;
use App\Middleware\LastPagePaginatorMiddleware;
use App\Source\AModule;

class Module extends AModule
{
    const MODULE_NAME = 'customizer_admin_panel';

    public function registerRoute()
    {
    	$this->app->options('/ajax', 'App\Controllers\Admin\UniversalAjaxController:update')->add('App\Middleware\CheckAjaxMiddleware')->setName('ajax.custom.field');
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
            $page = new \App\Middleware\LastPagePaginatorMiddleware($event->getContainer());
            $page->setOption(1, $event->getParams()['allParams']);
        });
    }
}
