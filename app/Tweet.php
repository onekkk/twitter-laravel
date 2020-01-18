<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tweet extends Model
{
    protected $table = 'tweets';

   	protected $guarded = [
		'id',
		'created_at',
		'updated_at',
	];              


    public function author()
    {
	    return $this->belongsTo('App\User', 'author_id');
    }

    public function img_url(): ?String
    {
    	    $base_url = env("AWS_CLOUD_FRONT_URL") . "/"; 
            if ($this->img_path == null) {
                    return null;
            }
    	    return $base_url . $this->img_path;

    }


}
