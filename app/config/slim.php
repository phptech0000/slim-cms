<?php

return [
	'slim' => [
		'settings' => [
	        'displayErrorDetails' => true, // @require: true | false
	        'debug' 	 => true, // @require: true | false
	        'log_system' => 'db', // @require: file | db
	        'db_driver'  => 'sqlite', // (use config section "db_drivers") @require: sqlite | mysql
	    ],
	    'db_driver' => 'sqlite', // (use config section DB) @require: sqlite | mysql
	]
];