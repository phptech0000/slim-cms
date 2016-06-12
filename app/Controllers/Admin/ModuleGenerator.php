<?php

namespace App\Controllers\Admin;

class ModuleGenerator extends BaseController
{
    public function index($req, $res)
    {
        $this->controllerName = $req->getAttribute('route')->getName();
        $this->resourse = false;
        $this->initRoute($req, $res);
        $this->data['h1'] = 'Module Generator';

        $this->view->render($res, 'admin\moduleGenerator.twig', $this->data);
    }

    public function doAdd($req, $res)
    {
        $this->flash->addMessage('errors', 'Module exist!');
        $this->flash->addMessage('errors', 'Module system_name is empty!');

        $this->flash->addMessage('success', 'Module create!');
        return $res->withStatus(301)->withHeader('Location', $this->router->pathFor('developers.module.generator'));
    }
}