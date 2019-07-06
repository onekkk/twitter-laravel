@extends('base.base') 

@section('head')
<link rel="stylesheet" type="text/css" href="./css/index.css">
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <div id="user_info" class="col-md-12">
            @if ($auth->img_path != null)
                <img src="{{$auth->img_path}}" alt="" width="" class="img_circle"> 
            @else
                <img src="storage/users_images/unknown.jpg" alt="" width="" class="img_circle">
            @endif
            <p id="user_name"> <strong>{{$auth->name}}</strong> <br>{{$auth->user_id}}</p>
            <p>自己紹介</p>
            <p>{{$auth->body}}</p>
            <ul class="nav nav-pills nav-justified" id="nav_btn">
                <li class="nav-item"> <a class="nav-link" href="{{ route('profile') }}" id="bt_pf">プロフィール</a> </li>
                <li class="nav-item"> 
                    <a class="nav-link" href="{{ route('logout') }}" id="bt_lg" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    ログアウト
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>               
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-11" id="contents">
                <form action="{{ route('home') }}" method="post" enctype="multipart/form-data" id="tw_form">
                    @csrf
                    <div class="form-group row"> <label for="user_name" class="col-sm-2 col-form-label">ツイート</label>
                        <div class="col-sm-10"> <textarea type="text" class="form-control" name="body" id="body" maxlength='140' title="" placeholder="140文字以内で入力してください">{{ old('body')}}</textarea> </div>
                        @if ($errors->has('body'))
                            <span class="alert alert-danger col-md-11">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
                            <br>
                        @endif
                    </div>
                    <div class="form-group row"> <label for="tweet_img" class="col-sm-2 col-form-label">画像</label>
                        <div class="col-sm-10"> 
                            <input type="file" name="tweet_img" class="" accept=".jpg,.gif,.png,image/gif,image/jpeg,image/png"> 
                        </div>
                        @if ($errors->has('tweet_img'))
                            <span class="alert alert-danger col-md-11">
                                <strong>{{ $errors->first('tweet_img') }}</strong>
                            </span>
                            <br>
                        @endif
                    </div> 
                    <input type="submit" class=" float-right btn btn-primary" id="contribution" name="contribution" value="投稿"> 
                </form>
                @foreach ($tweets as $tweet)
                    <div class="tweet">
                        <div class="tw_user"> 
                            @if ($tweet->author_img != null)
                                <img src="{{$tweet->author_img}}" alt="" width="50px" class="img_circle"> 
                            @else
                                <img src="storage/users_images/unknown.jpg" alt="" width="50px" class="img_circle">
                            @endif
                            
                                <a href="{{ route('userDetail', ['id' => $tweet->author_id]) }}">
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
        <!-- <form action="" method="get" class="col-md-12">
            <div class="input-group" id="serach_bar"> <input type="text" class="form-control" name="serach_text" placeholder="" value="{$search_text}">
                <div class="input-group-append"> <input class="btn btn-primary" type="submit" name="serach" value="検索"> </div>
            </div>
        </form> -->
    </div>
</div> @endsection