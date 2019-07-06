<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

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
            $path = $request->file('user_img')->store('public/users_images');
            $img_path = "storage/users_images/".basename($path);
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
