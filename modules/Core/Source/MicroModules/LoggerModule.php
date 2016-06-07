<?php

namespace Core\Source\MicroModules;

use App\Source\AModule;
use Illuminate\Support\Facades\DB;
use Core\Source\Libs\Logger\LoggerSystem;
use Core\Source\Libs\Logger\SqliteMonologHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use MySQLHandler\MySQLHandler;

class LoggerModule extends AModule
{
    const MODULE_NAME = 'logger';

    protected $registerDi = false;

    public function initialization()
    {
        $this->registerDi();

        $this->container->get('logger')->info("Logger initialization", []);
        $this->container->get('logger')->info("Request Url", [$_SERVER['REQUEST_URI']]);
        $this->container->get('logger')->info("Request Method", [$_SERVER['REQUEST_METHOD']]);

        foreach ($this->container->modules as $name) {
            if( $name == 'core' ){
                continue;
            }

            $this->container->dispatcher->addListener('module.' . $name . '.beforeInitialization', function ($event) {
                $event->getLogger()->addInfo("action beforeInitialization", [$event->getParam()->getName()]);
            });

            $this->container->dispatcher->addListener('module.' . $name . '.afterInitialization', function ($event) {
                $event->getLogger()->addInfo("action afterInitialization", [$event->getParam()->getName()]);
            });
        }

        $this->container->dispatcher->addListener('app.afterRun', function ($event){
            $workTime = round((microtime(true) - $GLOBALS['startTime']), 3);
            $logger = $event->getLogger();
            $logger->stat("work time: ", [$workTime . 's']);
            $logger->stat("memory usage: ", [memoryFormat(memory_get_usage())]);
            $logger->stat("max memory usage: ", [memoryFormat(memory_get_peak_usage())]);
            $logger->stat("Stop Application", []);
        });
    }

    public function registerDi()
    {
        if ($this->registerDi) {
            return;
        }

        // Register service provider
        $this->container['logger'] = function ($c) {
            $arLogerConf = ($c->settings['use_log'])?$c->settings['register_log']:[];
            $logger = new LoggerSystem(new Logger('slimcms_core'), $arLogerConf);//new Logger('slimcms_core');

            $filename = ($c->settings['log_filename'])?$c->settings['log_filename']:"app.log";
            $handler = new StreamHandler(ROOT_PATH . "log/".$filename);
            if ($c['settings']['log_system'] == 'db') {
                $handler = new MySQLHandler(DB::connection()->getPdo(), "logging");
                if (DB::connection()->getDriverName() == 'sqlite') {
                    $handler = new SqliteMonologHandler(DB::connection()->getPdo(), "logging");
                }
            }

            if( $c['settings']['use_log'] ){
                $logger->pushHandler($handler);
            }

            return $logger;
        };

        $this->registerDi = true;
    }

}
