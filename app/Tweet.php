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
	    if ($this->img_path == null) {
		    return null;
	    }
            return Storage::disk('s3')->url($this->img_path);
    }


}
