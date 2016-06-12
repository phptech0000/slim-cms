<?php

namespace App\Controlles\Admin;

use App\Controllers\Admin\BaseController;

class ModuleGenerator extends BaseController
{
    public function index($req, $res){
        $this->controllerName = $req->getAttribute('route')->getName();
        $this->resourse = false;
        $this->initRoute($req, $res);
        $this->data['h1'] = 'Module Generator';

        $this->view->render($res, 'admin\moduleGenerator.twig', $this->data);
    }
}