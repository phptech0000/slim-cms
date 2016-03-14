<?php

namespace App\Models;

class Users extends BaseModel
{
	protected $table = 'users';

	protected $fillable = ['login', 'email', 'password', 'active'];
}