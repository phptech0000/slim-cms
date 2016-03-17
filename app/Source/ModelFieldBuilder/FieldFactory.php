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
			case 'radio':
				return new RadioField($obj);
			case 'select':
			case 'multiselect':
				return new SelectField($obj);
			case 'text':
			case 'html':
				return new TextField($obj);
			default:
				return new StringField($obj);
		}
	}
}