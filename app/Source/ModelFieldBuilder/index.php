<?php

set_include_path('./src/');
spl_autoload_extensions('.php');
spl_autoload_register();

echo "<pre>";

$fieldBuilder = new BuildFields();

$fjson = file_get_contents('fieldsAnnotation/user.json');
$data = json_decode($fjson);

foreach ($data as $item) {
	$fieldBuilder->add($item);
}

print_r($fieldBuilder->getAll());
//$fieldBuilder->add('text')->add('action');