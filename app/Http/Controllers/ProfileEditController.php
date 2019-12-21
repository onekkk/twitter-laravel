<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ProfileEditController extends Controller
{
    public function index(Request $request)
    {
    	$auth = Auth::user(); //ユーザ情報取得
        return view('profile_edit', ['auth' => $auth,]);
    }

    public function update(Request $request)
    {
    	$validatedData = $request->validate([
        	'user_name' => 'required|string',
        	'user_body' => 'required|string',
        	'user_img' => 'file|image|mimes:jpeg,png,gif',
    	]);

    	$auth = Auth::user(); 
    	$req = $request;

    	$img_path = null;
	if ($request->hasFile('user_img')) {
	    $req_img = $request->file('user_img');
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
	      $resize_path = public_path('image/convert.jpg'); // 保存先を指定

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

	      $img_path = Storage::disk('s3')->putFile('/profile_img', new File($resize_path), 'public');
	      unlink($resize_path);
	    }else{
	      $img_path = Storage::disk('s3')->putFile('/profile_img', $request->file('user_img'), 'public');
	    }
	}


    	User::where('id', $auth['id'])
          ->update([
          	      'name' => $req['user_name'],
          	      'body' => $req['user_body'],
          	      'img_path' => $img_path,

          	    ]);

         return redirect('/');
    }

}
