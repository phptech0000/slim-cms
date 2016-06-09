<?php

namespace CoreModule\Source\MicroModules;

use App\Models\Pages;
use App\Source\AModule;
use App\Source\RouteSystem\PageResource;
use App\Source\RouteSystem\PageRouteCollection;


class PublicModule extends AModule
{
    const MODULE_NAME = 'public_cms';

    public function registerRoute()
    {
        $pages = Pages::where('active', 1)->orderBy('id', 'asc')->get()->toArray();

        if( empty($pages) )
            return;

        $this->container->get('router')->removeNamedRoute('home');

        while ($page = array_shift($pages)) {
            $url = $page['url_prefix'].'/'.$page['code'];
            $controller = 'homeAction';
            if( $page['id']>1 )
                $controller = 'detailAction';

            if( !$page['category_id'] )
                PageRouteCollection::add(new PageResource($url, $controller, $page['id']));
        }
    }

    public function afterInitialization(){
        parent::afterInitialization();

        $this->menuCreator();

        $this->container->dispatcher->addListener('app.beforeRun', function ($event){
            PageRouteCollection::register($event->getApp());
        }, -980);
    }

    protected function menuCreator(){
        $this->container->dispatcher->addListener('publiccontroller.menu.logic', function ($event) {
            $items = Pages::where('show_in_menu', 1)->where('active', 1)->orderBy('sort', 'asc')->get();

            $name = '';
            if($route = $event->getParams()->request->getAttribute('route'))
                $name = $route->getName();

            $menu = $event->getParams()->menu;
            foreach ($items as $item) {
                $menu[] = [
                    'name' => $item->name_for_menu,
                    'current' => (bool)($name=='page.'.$item->id),
                    'section' => $item->category_id,
                    'code' => $item->code,
                    'id' => $item->id,
                    'url' => 'page.'.$item->id,
                ];
            }
            
            $event->getParams()->menu = $menu;
        });
    }
}
