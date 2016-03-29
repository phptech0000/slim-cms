<?php
namespace App\Source\Events;

use Monolog\Logger;
use Symfony\Component\EventDispatcher\Event;

class BaseLoggerEvent extends Event
{
    /**
     * @var Monolog\Logger
     */
    protected $logger;

    protected $undefinedObject;

    public function __construct(Logger $logger, $param = null)
    {
        $this->logger = $logger;
        $this->undefinedObject = $param;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getParam()
    {
        return $this->undefinedObject;
    }
}
