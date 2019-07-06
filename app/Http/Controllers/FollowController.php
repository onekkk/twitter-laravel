<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Follow;


class FollowController extends Controller
{

    public function upload(Request $request){

    	$validatedData = $request->validate([
        	'follow' => 'required|integer',
        	'follower' => 'required|integer',
    	]);
    	
    	$req = $request->all();

    	if($req['follow_is'] == "true"){
            //delete
            $del = Follow::where('follow_id', $req['follow'])->where('follower_id', $req['follower'])->delete();
    	}else{
            //insert
            Follow::create([
                'follow_id' => $req['follow'],
                'follower_id' => $req['follower'],
            ]);
    	}
    }

}
