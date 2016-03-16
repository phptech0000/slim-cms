<?php

namespace App\Source\ModelFieldBuilder;

class HiddenField extends AField
{
	protected $allowTypes = ['hidden'];
	protected $defaultType = 'hidden';

	public function __construct(\stdClass $obj){
		parent::__construct($obj);
	}

	public function __toString(){
		if( !$this->visible || $this->name=='default' )
			return '';

		$str = sprintf('<input type="%s" name="%s" value="%s" #>', $this->type, $this->name, $this->value);
		
		return $this->toString($str);
	}
}