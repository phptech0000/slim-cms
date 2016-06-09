<?php

return [
    'slim' => [
        'settings' => [
            'db_driver' => 'sqlite', // (use config section DB) @require: sqlite | mysql
            'displayErrorDetails' => true, // @require: true | false
            'debug' => true, // @require: true | false
            'use_log' => true, // @require: true | false
            'log_system' => 'file', // @require: file | db
            'log_filename' => 'app.log',
            'register_log' => ['info', 'statistic', 'error'], // @require: info, statistic, error
            'determineRouteBeforeAppMiddleware' => true,
            'protect_double_route_register' => true,
        ],
    ],
];
