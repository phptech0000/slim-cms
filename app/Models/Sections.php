<?php

namespace App\Models;

class Sections extends BaseModel
{
	protected $table = 'sections';

	protected $fillable = ['name', 'code', 'parent_id', 'detail_text', 'detail_picture', 'show_in_menu', 'name_for_menu', 'active', 'sort'];
}