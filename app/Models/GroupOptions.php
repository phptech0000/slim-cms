<?php

namespace App\Models;

class GroupOptions extends BaseModel
{
	protected $table = 'options_group';

	protected $fillable = ['name', 'description', 'active'];
}