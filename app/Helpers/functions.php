<?php

function p($data, $die=false){
	if (function_exists("dump")) {
		dump($data);
		echo '<style>pre.sf-dump{font-size:16px;}</style>';
	} else {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}

	if( $die ) die;
}

function memoryFormat($m){
	if ($m < 1024)
    	$m = $m." b";
	elseif ($m < 1048576)
	    $m = round($m/1024,2)." kb";
	else
	    $m = round($m/1048576,2)." mb";

	return $m;
}