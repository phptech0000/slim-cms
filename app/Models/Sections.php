<?php

namespace App\Models;

class Sections extends BaseModel
{
    protected $table = 'sections';

    protected static $reCount = true;

    const PATH_DELIMITER = '/';

    protected $fillable = ['name', 'code', 'parent_id', 'detail_text', 'detail_picture', 'show_in_menu', 'name_for_menu', 'active', 'sort', 'path'];

    public function save(array $options = array())
    {
        if ($this->parent_id) {
        	$item = $this->find($this->parent_id);
        	$path = explode(self::PATH_DELIMITER, $item->path);
        	
        	if( isset($this->id) && in_array($this->id, $path) ){
        		$GLOBALS['app']->getContainer()->flash->addMessage('errors', 'Create recursion section');
        		return;
        	}
        	array_pop($path);
        	$path[] = $this->parent_id;
        	$path[] = '';
        	$this->path = implode(self::PATH_DELIMITER, $path);
        } else {
        	$this->path = self::PATH_DELIMITER.'0'.self::PATH_DELIMITER;
        }

        parent::save($options);

	    if( self::$reCount ){
            $items = $this->where('path', 'LIKE', '%/'.$this->id.'/%')->orderBy('path', 'ASC')->get();
            self::$reCount = false;
            if($items){
            	foreach ($items as $item) {
            		$item->save();
            	}
            }
            self::$reCount = true;
        }
    }
}
