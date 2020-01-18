<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tweet;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        if($auth['img_path'] != null){
            $auth['img_path'] = Storage::disk('s3')->url($auth['img_path']);
        }else{
            $auth['img_path'] = Storage::disk('s3')->url('profile_img/unknown.jpg');
        }

    	$user_id = (int)$userId;

        if((int)$auth['id'] == $user_id){
            return redirect('/profile');
        }

    	$user = User::select('*')
    		->where('id', $user_id)
    		->first();

        $tweets = Tweet::join('users', 'tweets.author_id', '=', 'users.id')
            ->select('tweets.*', 'users.id as author_id', 'users.user_id as author_user_id', 'users.name as author_name', 'users.img_path as author_img')
            ->where('tweets.author_id', $user_id)
            ->orderBy('tweets.created_at', 'desc')
            ->paginate(3);

        $follow_is = Follow::query()->where('follow_id', $auth['id'])->where('follower_id', $user_id)->count();

        return view('userDetail', ['auth' => $auth, 'tweets' => $tweets, 'user' => $user, 'follow_is' => $follow_is]);
    }


}
