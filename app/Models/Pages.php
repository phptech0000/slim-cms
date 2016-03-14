<?php

namespace App\Models;

class Pages extends BaseModel
{
	protected $table = 'pages';

	protected $fillable = ['name', 'code', 'url_prefix', 'category_id', 'preview_text', 'detail_text', 'preview_picture', 'detail_picture', 'show_in_menu', 'name_for_menu', 'active', 'slogan', 'fullname', 'sort'];
}