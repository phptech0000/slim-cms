<?php

namespace App\Controllers\Admin;

use App\Helpers\SessionManager as Session;

class DashboardController extends BaseController
{
	public function index($req, $res){
		$this->data['h1'] = 'Dashboard';
		$this->view->render($res, 'admin\dashboard.twig', $this->data);
	}
}
