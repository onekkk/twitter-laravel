@extends('base.base') 

@section('head')
<link rel="stylesheet" type="text/css" href="/css/profile.css">
@endsection
@section('content')
<div class="row">
    <div class="col-md-3">
        <div id="user_info" class="col-md-12">
            @if ($auth->img_path != null)
                <img src="{{$auth->img_path}}" alt="" width="100px" class="img_circle"> 
            @else
                <img src="{{$auth->img_path}}" alt="" width="" class="img_circle">
            @endif
            <p id="user_name">
                <strong>{{$auth->name}}</strong>
                <br>@ {{$auth->user_id}}
            </p>
            <p>プロフィール</p>
            <p>{{$auth->body}}</p>
            <a href="/profile_edit">プロフィール編集</a>
            
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-11" id="content">
                @foreach ($tweets as $tweet)
                    <div class="tweet">
                        <div class="tw_user"> 
                            @if ($tweet->author_img != null)
                                <img src="../{{$tweet->author_img}}" alt="" width="50px" class="img_circle"> 
                            @else
                                <img src="../storage/users_images/unknown.jpg" alt="" width="50px" class="img_circle">
                            @endif
                            
                                <a href="user_detail.php?detail_user={{$tweet->author_id}}">
                                    {{$tweet->author_user_id}}
                                    <strong>@ {{$tweet->author_name}}</strong>
                                </a> 
                        </div>
                        <div>
                            <p>{{$tweet->body}}</p>
                            @if ($tweet->img_path != null)
                                <img src="{{$tweet->img_path}}" alt="" width="100px">
                            @endif
                            
                            </div>
                    </div>
                @endforeach
                {{ $tweets->links() }}
            </div>
        </div>
                    
    </div>
    <div class="col-md-3">
    </div>
</div> 
@endsection
