<?php

use App\Models\Pages;
use App\Source\RouteSystem\PageResource;
use App\Source\RouteSystem\PageRouteCollection;

$pages = Pages::where('active', 1)->orderBy('id', 'asc')->get()->toArray();

if( empty($pages) )
	return;

while ($page = array_shift($pages)) {
	$url = $page['url_prefix'].'/'.$page['code'];
	$controller = 'homeAction';
	if( $page['id']>1 )
		$controller = 'detailAction';

	PageRouteCollection::add(new PageResource($url, $controller, $page['id']));
}

PageRouteCollection::register($app);