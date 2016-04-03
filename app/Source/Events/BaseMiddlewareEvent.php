<?php
namespace App\Source\Events;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Container;
use Symfony\Component\EventDispatcher\Event;

class BaseMiddlewareEvent extends Event
{
    /**
     * @var Slim\Container
     */
    protected $container;

    public function __construct(Container $container, ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->container = $container;
        $this->setRequest($request);
        $this->setResponse($response);
    }

    /**
     * @return \Slim\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function getLogger()
    {
        return $this->container->logger;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

}
