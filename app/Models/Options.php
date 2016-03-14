<?php

namespace App\Models;

class Options extends BaseModel
{
	protected $table = 'options';

	protected $fillable = ['options_group_id', 'name', 'description', 'value', 'type',  'code'];
}