<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tweet;
use Illuminate\Support\Facades\DB;
use App\Follow;

class UserDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $userId)
    {
    	$auth = Auth::user(); //ユーザ情報取得

    	$user_id = (int)$userId;

        if((int)$auth['id'] == $user_id){
            return redirect('/profile');
        }

    	$user = DB::table('users')
    		->select('*')
    		->where('id', $user_id)
    		->first();

        $tweets = DB::table('tweets')
            ->join('users', 'tweets.author_id', '=', 'users.id')
            ->select('tweets.*', 'users.id as author_id', 'users.user_id as author_user_id', 'users.name as author_name', 'users.img_path as author_img')
            ->where('tweets.author_id', $user_id)
            ->orderBy('tweets.created_at', 'desc')
            ->paginate(3);

        $follow_is = Follow::query()->where('follow_id', $auth['id'])->where('follower_id', $user_id)->count();

        return view('userDetail', ['auth' => $auth, 'tweets' => $tweets, 'user' => $user, 'follow_is' => $follow_is]);
    }


}
