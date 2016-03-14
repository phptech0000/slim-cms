<?php

namespace App\Controllers\Admin;

use App\Helpers\SessionManager as Session;
use \Illuminate\Database\Capsule\Manager as Schema;
use \Psr\Http\Message\ServerRequestInterface as request;
use App\Source\ModelsFactory;

class UniversalController extends BaseController
{
	public function index(request $req, $res){
		$this->initRoute($req);

		$model = ModelsFactory::getModelWithRequest($req);

		$this->data['items'] = $model->paginate(10);//$model->all();
		$this->data['items']->setPath($this->router->pathFor($this->data['all_e_link']));
		$this->data['fields'] = $this->getFields($model->getColumnsNames(), array('id'));

		$this->view->render($res, 'admin\dataTables.twig', $this->data);
	}

	public function add(request $req, $res){
		$this->initRoute($req);

		$model = ModelsFactory::getModelWithRequest($req);
		$this->data['fields'] = $this->getFields($model->getColumnsNames());

		$this->view->render($res, 'admin\addTables.twig', $this->data);
	}

	public function edit(request $req, $res, $args){
		$this->initRoute($req);

		$model = ModelsFactory::getModelWithRequest($req);
		$this->data['fields'] = $this->getFields($model->getColumnsNames(), ['id']);
		$this->data['fieldsValues'] = $model->find($args['id']);
		$this->data['type_link'] = $this->data['save_link'];

		$this->view->render($res, 'admin\addTables.twig', $this->data);
	}

	public function doAdd(request $req, $res, $args){
		$this->initRoute($req);
		$model = ModelsFactory::getModelWithRequest($req, $req->getParsedBody());
		$model->save();
		
		$this->flash->addMessage('success', $this->controllerName.' success added!');

		return $res->withStatus(301)->withHeader('Location', $this->router->pathFor('list.'.$this->controllerName));
	}

	public function doEdit(request $req, $res, $args){
		$this->initRoute($req);
		$reqData = $req->getParsedBody();
		$model = ModelsFactory::getModelWithRequest($req);
		$model = $model->find($reqData['id']);

		$model->update($reqData);
		$this->flash->addMessage('success', $this->controllerName.' success edited!');

		return $res->withStatus(301)->withHeader('Location', $this->router->pathFor('list.'.$this->controllerName));
	}

	public function doDelete(request $req, $res, $args){
		$this->initRoute($req);
		$model = ModelsFactory::getModelWithRequest($req);
		$model = $model->find($args['id']);
		$model->delete();

		$this->flash->addMessage('success', $this->controllerName.' success deleted!');

		return $res->withStatus(301)->withHeader('Location', $this->router->pathFor('list.'.$this->controllerName));
	}
}
