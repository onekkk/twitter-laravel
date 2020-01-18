<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Tweet;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\User;
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
        $tweets = Tweet::join('users', 'tweets.author_id', '=', 'users.id')
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
        //if ($request->hasFile('tweet_img')) {
        //    $img_path = Storage::disk('s3')->putFile('/tweet_images', $request->file('tweet_img'), 'public');
        //}
	if ($request->hasFile('tweet_img')) {
	    $req_img = $request->file('tweet_img');
	    $original_img = getimagesize($req_img);
	    list($original_w, $original_h, $type) = $original_img;
	    
	    $convert_with = 200; // 変換後の横幅
	    if($original_w > $convert_with){
	      $convert_height = ($convert_with / $original_w) * $original_h;
	      switch ($type) {
		case IMAGETYPE_JPEG:
        	  $original_image = imagecreatefromjpeg($req_img);
                  break;
                case IMAGETYPE_PNG:
                  $original_image = imagecreatefrompng($req_img);
                  break;
                case IMAGETYPE_GIF:
                  $original_image = imagecreatefromgif($req_img);
                  break;
		default:
		  $original_image = -1;
		  break;
	      }
	      if($original_image == -1){
	      	return redirect('/');
	      }
	      $convert_image = imagecreatetruecolor($convert_with, $convert_height);
	      imagecopyresampled($convert_image, $original_image, 0,0,0,0, $convert_with, $convert_height, $original_w, $original_h);
	      $resize_path = public_path('image/tweet_convert.jpg'); // 保存先を指定

	      switch ($type) {
    		case IMAGETYPE_JPEG:
        	  imagejpeg($convert_image, $resize_path);
        	  break;
    		case IMAGETYPE_PNG:
        	  imagepng($convert_image, $resize_path, 9);
        	  break;
    		case IMAGETYPE_GIF:
        	  imagegif($convert_image, $resize_path);
        	  break;
	      }

	      $img_path = Storage::disk('s3')->putFile('/tweet_images', new File($resize_path), 'public');
	      unlink($resize_path);
	    }else{
	      $img_path = Storage::disk('s3')->putFile('/tweet_images', $request->file('tweet_img'), 'public');
	    }
	}
        

        Tweet::create([
            'author_id' => $auth['id'],
            'body' => $req['body'],
            'img_path' => $img_path,
        ]);

        return redirect('/');

    }
}
