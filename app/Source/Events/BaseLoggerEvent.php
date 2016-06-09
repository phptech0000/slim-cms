<?php
namespace App\Source\Events;

use CoreModule\Source\Libs\Logger\LoggerSystem;
use Symfony\Component\EventDispatcher\Event;

class BaseLoggerEvent extends Event
{

    /**
     * @var Modules\Core\Source\Libs\Logger\LoggerSystem
     */
    protected $logger;

    protected $undefinedObject;

    public function __construct(LoggerSystem $logger, $param = null)
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
