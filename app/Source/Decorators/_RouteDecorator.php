<?php

namespace App\Source\Decorators;

use Slim\Router;

class RouteDecorator extends Router
{
	public function removeRoute($identifier)
	{
		unset($this->routes[$identifier]);
	}
}