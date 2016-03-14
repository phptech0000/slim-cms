<?php
namespace App\Middleware;

class RouteNameMiddleware
{
    protected $c; // container

    public function __construct($c)
    {
        $this->c = $c; // store the instance as a property
    }

    public function getName($request, $response, $next)
    {
        // create a new property in the container to hold the route name
        // for later use in ANY controller constructor being 
        // instantiated by the router
        $this->c['currentRoute'] = $request->getAttribute('route')->getName();
        $next($request, $response);
        $this->c['currentRoute'] = $request->getAttribute('route')->getName();
        return $next($request, $response);
    }
}