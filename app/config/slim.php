<?php

return [
    'slim' => [
        'settings' => [
            'displayErrorDetails' => true, // @require: true | false
            'debug' => true, // @require: true | false
            'log_system' => 'file', // @require: file | db
            'use_log' => true,
            'determineRouteBeforeAppMiddleware' => true,
            'protectDoubleRouteRegister' => true,
        ],
        'db_driver' => 'sqlite', // (use config section DB) @require: sqlite | mysql
    ],
];
