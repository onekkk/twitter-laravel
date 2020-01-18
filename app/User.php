<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'email', 'password', 'body', 'img_path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function cloudFrontUrl($file_name){
	    $url = env("AWS_CLOUD_FRONT_URL") . "/". $file_name;
	    //var_dump($url);exit;
	    return $url;
    }

    public function img_url(): ?String
    {
            if ($this->img_path == null) {
                    return null;
            }
    	    return env("AWS_CLOUD_FRONT_URL") . "/". $this->img_path;
    }
}
