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

	public delete function(){
		self::create([
                'follow_id' => $req['follow'],
                'follower_id' => $req['follower'],
        ]);
	}

	public delete function(){
		$del = self::where('follow_id', $req['follow'])->where('follower_id', $req['follower'])->delete();
	}


}
