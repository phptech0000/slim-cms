<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 6/11/16
 * Time: 1:55 PM
 */

namespace Modules\Breadcrumb;


class ExtModule extends Module
{
    protected $m;
    public function __construct($m){
        $this->m = $m;
    }

    public function t(){
        return "ext ".$this->m->t();
    }
}