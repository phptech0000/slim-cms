<?php

namespace App\Source\ModelFieldBuilder;

class FieldFactory implements Interfaces\IFieldFactory
{
	public static function getField(\stdClass $obj){
		return new TextField($obj);
	}
}