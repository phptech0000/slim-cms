<?php

return [
    'view' => [
		'template_path' => APP_PATH.'templates',
		'twig' => [
		    'cache' => CACHE_PATH.'twig',
		    'debug' => true,
		    'auto_reload' => true,
		]
	]
];