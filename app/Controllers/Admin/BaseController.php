<?php

namespace App\Controllers\Admin;

use Illuminate\Pagination\Paginator;
use App\Helpers\SessionManager as Session;

class BaseController
{
	protected $controllerName = '';
	protected $containerSlim;


	protected $data = array(
			'title' => '',
			'description' => '',
			'keywords' => '',
			'h1' => '',
			'flash' => array(),
			'page_counts' => [5,10,15,25,50,100,150],
		);

	public function __construct($container){
		$this->containerSlim = $container;

		$this->view = $container->get('view');
		$this->csrf = $container->get('csrf');
		$this->flash = $container->get('flash');
		$this->router = $container->get('router');

		if( $messages = $this->containerSlim->get('flashMess') ){
			foreach ($messages as $key => $value) {
				$this->data[$key] = $value[0];
			}
		}

		$this->init();
	}

	protected function init(){
		if( !isset($this->controllerName) || !$this->controllerName )
			return;

		$arDataContr = array('title', 'description', 'keywords', 'h1');

		foreach ($arDataContr as $name) {
			$this->data[$name] = $this->controllerName;
		}

		$this->data['create_link'] = 'add.'.$this->controllerName;
		$this->data['all_e_link']  = 'list.'.$this->controllerName;
		$this->data['edit_link']   = 'edit.'.$this->controllerName;
		$this->data['store_link']  = 'store.'.$this->controllerName;
		$this->data['save_link']   = 'save.'.$this->controllerName;
		$this->data['delete_link'] = 'delete.'.$this->controllerName;
	}

	protected function initRoute($req){
		$s = $req->getAttribute('route')->getName();

		$this->containerSlim->get('logger')->addInfo("Run admin page: ", [Session::get('user')['login']]);
		$this->containerSlim->get('logger')->addInfo("Get route: ", [$s]);

		$current_page = $_REQUEST['page'];

	    Paginator::currentPageResolver(function() use ($current_page) {
	        return $current_page;
	    });

	    if( $_REQUEST['count_page'] ){
	    	Session::push('admin_panel.count_page', $_REQUEST['count_page']);
	    }

	    $this->pagecount = Session::get('admin_panel.count_page');
	    $this->data['page_count'] = $this->pagecount; 

		$this->controllerName = substr($s, strpos($s, '.')+1);
		$this->init();
		$this->csrf($req);
	}

	protected function csrf($req){
		$this->data['csrf'] = new \stdClass();
    	$this->data['csrf']->nameKey = $this->csrf->getTokenNameKey();
    	$this->data['csrf']->valueKey = $this->csrf->getTokenValueKey();
    	$this->data['csrf']->name = $req->getAttribute('csrf_name');
    	$this->data['csrf']->value = $req->getAttribute('csrf_value');
	}

	protected function getFields(array $arFields, $arSave=array(), $arHide=array()){
		$arHide = array_merge($arHide, array('id', 'created_at', 'updated_at'));

		return array_diff(
			$arFields, 
			array_diff($arHide, $arSave)
		);
	}
}