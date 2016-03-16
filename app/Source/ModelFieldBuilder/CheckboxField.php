<?php

namespace App\Source\ModelFieldBuilder;

class CheckboxField extends AField
{
	protected $allowTypes = ['checkbox'];
	protected $defaultType = 'checkbox';
	public $values = ['1'=>1]

	public function __construct(\stdClass $obj){
		parent::__construct($obj);
	}

	public function __toString(){
		if( !$this->visible || $this->name=='default' )
			return '';

		$str = sprintf('<input type="%s" name="%s" value="%s" #>', $this->type, $this->name, $this->value);
		
		if( $this->value!==null )
			$str = str_replace("#", "# value=\"".."\"", $str);

		if($this->placeholder)
			$str = str_replace("#", "# placeholder=\"".$this->placeholder."\"", $str);
		
		return str_replace("#", "", $str);
	}
}