<?php

namespace App\Controllers\Sites;
use App\Models\Options;
use App\Models\Pages;

/**
* 
*/
class BaseController
{
	protected $data = array(
			'title' => '',
			'description' => '',
			'keywords' => '',
			'h1' => '',
		);

	protected $request;
	protected $result;

	public function __construct($container){
		$this->view = $container->get('view');
		$this->csrf = $container->get('csrf');
		$this->flash = $container->get('flash');
		$this->router = $container->get('router');
		
		$this->addDataForView();
	}

	protected function csrf(){
		$this->data['csrf'] = new \stdClass();
    	$this->data['csrf']->nameKey = $this->csrf->getTokenNameKey();
    	$this->data['csrf']->valueKey = $this->csrf->getTokenValueKey();
    	$this->data['csrf']->name = $this->request->getAttribute('csrf_name');
    	$this->data['csrf']->value = $this->request->getAttribute('csrf_value');
	}

	public function addDataForView(){
		$this->data['options'] = Options::where('options_group_id', 2)->get()->toArray();
		$options = [];
		while($option = array_shift($this->data['options'])){
			$options[$option['code']] = $option;
		}
		$this->data['options'] = $options;

		$this->menu = Pages::where('show_in_menu', 1)->where('active', 1)->orderBy('sort', 'asc')->get()->toArray();
		$this->data['pageData'] = new \stdClass();
	}

	protected function setRequestResult($req, $res){
		$this->request = $req;
		$this->result  = $res;
	}

	protected function menuCreator(){
		$name = '';
		if($route = $this->request->getAttribute('route'))
			$name = $route->getName();
		
		$this->data['menu'] = array();
		
		if( !$this->menu )
			return;

		$menu = [];
		foreach ($this->menu as $item) {
			$menu[] = [
				'name' => $item['name_for_menu'],
				'current' => (bool)($name=='page.'.$item['id'] || $this->data['pageData']->category_id == $item['id']),
				'section' => $item['category_id'],
				'code' => $item['code'],
				'id' => $item['id'],
				'url' => ($item['category_id'])?'page.sp'.$item['category_id']:'page.'.$item['id'],
			];
		}

		$this->data['menu'] = $menu;
	}

	protected function setMetaData(){
		$this->data['title'] = $this->data['pageData']->name.' page';
		$this->data['description'] = $this->data['pageData']->preview_text;
		$this->data['keywords'] = '';
		$this->data['h1'] = $this->data['pageData']->name;

		if( $id = $this->data['pageData']->category_id ){
			$this->data['categoryData'] = Pages::find($id);
		}
	}

	protected function beforeRender(){
		$this->menuCreator();
		$this->setMetaData();
		$this->csrf();
	}

	protected function afterRender(){

	}

	public function render($template){
		$this->beforeRender();

		$this->view->render($this->result, $template, $this->data);

		$this->afterRender();

		return $this->result;
	}
}