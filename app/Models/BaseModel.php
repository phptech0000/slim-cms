<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model as ModelEloquent;
use \Illuminate\Database\Capsule\Manager as DB;

class BaseModel extends ModelEloquent
{
	public function getColumnsNames()
	{
	    $connection = DB::connection();
	    $connection->getSchemaBuilder();

	    $results = $connection->select('PRAGMA table_info('.$this->table.')');

	    return $connection->getPostProcessor()->processColumnListing($results);
	}
} 