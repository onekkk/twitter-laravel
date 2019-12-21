<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Tweet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $auth = Auth::user(); //ユーザ情報取得

        if($auth['img_path'] != null){
            $auth['img_path'] = Storage::disk('s3')->url($auth['img_path']);
        }else{
            $auth['img_path'] = Storage::disk('s3')->url('profile_img/unknown.jpg');
        }
        // $tweets = DB::table('tweets')
        //     ->join('users', 'tweets.author_id', '=', 'users.id')
        //     ->select('tweets.*', 'users.id as author_id', 'users.user_id as author_user_id', 'users.name as author_name', 'users.img_path as author_img')
            
        //     ->where('author_id', '=', $auth['id'])
        //     ->orwhereIn('author_id', 
        //                         function ($query) use ($auth)
        //                         {
        //                             $query->select('follower_id')
        //                                   ->from('follows')
        //                                   ->where('follow_id', '=', (int)$auth['id']);
                                          
        //                         }
        //                     )
        //     ->orderBy('tweets.created_at', 'desc')
        //     ->paginate(20);


        //サブクエリからleftJoinに変更
        $tweets = DB::table('tweets')
            ->join('users', 'tweets.author_id', '=', 'users.id')
            ->leftJoin('follows', 'follows.follower_id', '=', 'tweets.author_id')
            ->select('tweets.*', 'users.id as author_id', 'users.user_id as author_user_id', 'users.name as author_name', 'users.img_path as author_img')
            ->where('tweets.author_id', '=', $auth['id'])
            ->orwhere('follows.follow_id', '=', $auth['id'])
            ->orderBy('tweets.created_at', 'desc')
            ->paginate(20);


            // ->toSql();

            // var_dump($tweets);
            // var_dump(str_replace("`", "", $tweets));
            // var_dump($auth['id']);
            // exit;

        return view('index', ['tweets' => $tweets, 'auth' => $auth]);
    }

    public function add(Request $request){
        $req = $request->all();

        //バリデーション

        $rules = [
            'body' => 'required|max:140',
            'tweet_img' => 'file|image|mimes:jpeg,png,gif',
        ];

        $messages = [
            'required' => '必須の入力フィールドです',
            'max' => '最大文字数を超えています',
            'file' => 'ファイルを指定してください',
            'image' => '画像を指定してください',
            'mimes' => '画像のファイル形式が違います',
        ];

        $validator = Validator::make($req, $rules, $messages)->validate();


        //Insert
        $auth = Auth::user(); //ユーザ情報取得

        $img_path = null;
        if ($request->hasFile('tweet_img')) {
            $img_path = Storage::disk('s3')->putFile('/tweet_images', $request->file('tweet_img'), 'public');
        }
        

        Tweet::create([
            'author_id' => $auth['id'],
            'body' => $req['body'],
            'img_path' => $img_path,
        ]);

        return redirect('/');

    }
}
