<?php
namespace App\Middleware;

class ResourseMetterMiddleware
{
    protected $c; // container
    protected static $startTime;
    protected static $before = false;

    public function __construct($c)
    {
        $this->c = $c; // store the instance as a property
    }

    public function metter($request, $response, $next)
    {
        self::$startTime = microtime(true);

        $response = $next($request, $response);

        $endTime = self::$startTime + microtime(true);
        $this->c->get('logger')->addInfo("Time work application: ", [$endTime]);

        
        return $response;
    }
}