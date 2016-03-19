<?php

function p($data, $die=false){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
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