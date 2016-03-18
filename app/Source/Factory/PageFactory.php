<?php

namespace App\Source\Factory;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Illuminate\Support\Str;
use App\Models\Pages;

/**
* 
*/
class PageFactory
{
	function __construct(){}

	public static function getPageWithRequest(Request $req){
		$pageId = self::getPageId($req->getAttribute('route')->getName());

		if( $pageId > 0 )
			return Pages::find($pageId);

		return new \stdClass();
	}

	protected static function getPageId($routeName){
		return (int)substr($routeName, strpos($routeName, '.')+1);
	}
}