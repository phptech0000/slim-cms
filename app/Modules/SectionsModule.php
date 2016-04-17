<?php

namespace App\Modules;

use App\Source\RouteSystem\AdminResource;
use App\Source\RouteSystem\AdminRouteCollection;
use App\Helpers\SessionManager as Session;
use App\Source\Factory\ModelsFactory;
use App\Models\Sections;
use App\Source\RouteSystem\PageResource;
use App\Source\RouteSystem\PageRouteCollection;

class SectionsModule extends AModule
{
    const MODULE_NAME = 'sections';

    public function registerRoute()
    {
        $sections = Sections::getAllGlobalActive()->keyBy('id')->toArray();

        if( empty($sections) )
            return;

        foreach ($sections as $section) {
            $url = array_filter(explode('/', $section['path']));

            foreach ($url as &$id) {
                $id = $sections[$id]['code'];
            }

            $url[-1] = '';
            $url[] = $section['code'];
            ksort($url);

            $url = implode('/', $url);

            PageRouteCollection::add(new PageResource($url.'/', 'sectionAction', 's'.$section['id']));
            PageRouteCollection::add(new PageResource($url.'/{pageCode}', 'detailAction', 'sp'.$section['id']));
        }
    }

    public function afterInitialization(){
        parent::afterInitialization();

        $this->adminPanelRouteRegister();

        $this->container->dispatcher->addListener('basecontroller.render.before', function ($event) {
            
            $arItems = $this->findFieldValues($event);
            if( !$arItems )
                return true;
            
            $model = ModelsFactory::getModel('sections');
            $arRes = $model->where('active', 1)->get();

            $data = [];

            foreach ($arRes as $item) {
                if( ($arItems['parent_id'] || null === $arItems['parent_id']) && 
                      $item->id != $event->getParams()['fieldsValues']->id
                ){
                    $data[$item->id] = $item->name;
                }
            }

            foreach($arItems as $name=>$values){
                $event->getParams()['ttt'][$name]->values = $values + $data;
            }
        });
    }

    protected function adminPanelRouteRegister(){
        if( Session::has('auth') && Session::get('auth') ){
            AdminRouteCollection::add(new AdminResource('sections'));
        }
    }

    protected function findFieldValues($event){
        $arFields = ['category_id', 'parent_id'];

        $arNames = [];
        foreach ($arFields as $name) {
            $v = $event->getParams()['ttt'][$name]->values;

            if($v)
                $arNames[$name] = $v;
        }

        return $arNames;
    }
}