<?php

namespace App\Source;

use \Slim\Container;

interface IModules
{
    /**
     * @param Container $container
     */
    public function __construct(Container $container);

    public function checkRequireModule();

    public function doInstallModule();

    public function doUninstallModule();

    public function initialization();
}
