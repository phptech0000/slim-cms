<?php

namespace App\Source\ModelFieldBuilder;

class FieldFactory implements Interfaces\IFieldFactory
{
	public static function getField(\stdClass $obj){
		if( !$obj->type )
			return false;

		switch ($obj->type) {
			case 'hidden':
				return new HiddenField($obj);
			case 'checkbox':
				return new CheckboxField($obj);
			default:
				return new TextField($obj);
		}
	}
}