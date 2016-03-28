<?php
namespace App\Middleware;

class CoreFirstLastMiddleware
{
    protected $c; // container

    public function __construct($c)
    {
        $this->c = $c; // store the instance as a property
        $this->logger = $c->get('logger');
    }

    public function core($request, $response, $next)
    {
        $this->c->dispatcher->dispatch('middleware.core.before');
        $response = $next($request, $response);
        $this->c->dispatcher->dispatch('middleware.core.after');
        return $response;
    }
}