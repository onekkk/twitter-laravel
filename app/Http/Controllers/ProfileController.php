<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tweet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
	    $auth = Auth::user(); //ユーザ情報取得
	    if($auth['img_path'] != null){
		    $auth['img_path'] = Storage::disk('s3')->url($auth['img_path']);
	    }else{
	    	$auth['img_path'] = Storage::disk('s3')->url('profile_img/unknown.jpg');
	    }

        $user_id = (int)$auth['id'];

        $tweets = DB::table('tweets')
            ->join('users', 'tweets.author_id', '=', 'users.id')
            ->select('tweets.*', 'users.id as author_id', 'users.user_id as author_user_id', 'users.name as author_name', 'users.img_path as author_img')
            ->where('tweets.author_id', $user_id)
            ->orderBy('tweets.created_at', 'desc')
            ->paginate(3);

	foreach($tweets as $twt){
	    if($twt->img_path != null){
	        $twt->img_path = Storage::disk('s3')->url($twt->img_path);
	    }

	    if($twt->author_img != null){
	    	$twt->author_img = Storage::disk('s3')->url($twt->author_img);
	    }else{
	    	$twt->author_img = Storage::disk('s3')->url('profile_img/unknown.jpg');
	    }
	}
        return view('profile', ['auth' => $auth, 'tweets' => $tweets,]);
    }
}
