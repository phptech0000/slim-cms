<?php

namespace App\Source\ModelFieldBuilder;

class FieldFactory
{
	public static function getField(\stdClass $obj){
		return new Field($obj);
	}
}