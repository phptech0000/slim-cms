<?php

namespace App\Modules;

use App\Source\RouteSystem\AdminResource;
use App\Source\RouteSystem\AdminRouteCollection;
use App\Helpers\SessionManager as Session;
use App\Source\Factory\ModelsFactory;

class SectionsModule extends AModule
{
    const MODULE_NAME = 'sections';

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
                if( $arItems['parent_id'] && 
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