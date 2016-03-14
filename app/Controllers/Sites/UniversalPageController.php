<?php

namespace App\Controllers\Sites;

use \Psr\Http\Message\ServerRequestInterface as request;
use App\Source\PageFactory;

class UniversalPageController extends BaseController
{
	public function homeAction(request $req, $res){
		$this->data['pageData'] = PageFactory::getPageWithRequest($req);
		$this->setRequestResult($req, $res);

		$this->render('public\main\pages\home.twig');
	}

	public function detailAction(request $req, $res){
		$this->data['pageData'] = PageFactory::getPageWithRequest($req);
		$this->setRequestResult($req, $res);

		$this->render('public\main\pages\detail_page.twig');
	}

	public function projectAction(request $req, $res){
		$this->data['pageData'] = PageFactory::getPageWithRequest($req);
		$this->setRequestResult($req, $res);

		$this->render('public\main\pages\project_page.twig');
	}

	public function notFound(request $req, $res){
		$this->setRequestResult($req, $res);

		return $this->render('public\main\pages\404.twig');
	}
}
