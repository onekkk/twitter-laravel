<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $table = 'tweets';

   	protected $guarded = [
		'id',
		'created_at',
		'updated_at',
	];              

}
