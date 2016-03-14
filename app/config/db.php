<?php

return [
	'db' => [
		'mysql' => [
		    'driver' => 'mysql',
		    'host' => '127.0.0.1',
		    'database' => '',
		    'username' => '',
		    'password' => '',
		    'collation' => 'utf8_general_ci',
		    'prefix' => ''
		],
		'sqlite' => [
		    'driver'   => 'sqlite',
		    'database' => RESOURCE_PATH.'database/main.sqlite',
		    'prefix'   => '',
		]
	]
];