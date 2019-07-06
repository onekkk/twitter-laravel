<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'follows';

   	protected $guarded = [
		'id',
		'created_at',
		'updated_at',
	];          
}
