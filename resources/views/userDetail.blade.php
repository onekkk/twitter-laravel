@extends('base.base') 

@section('head')
<link rel="stylesheet" type="text/css" href="/css/user_detail.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <div id="user_info" class="col-md-12">
            @if ($user->img_path != null)
                <img src="{{$auth->img_path}}" alt="" width="100px" class="img_circle"> 
            @else
                <img src="{{$auth->img_path}}" alt="" width="" class="img_circle">
            @endif
            <p id="user_name">
                <strong>{{$user->name}}</strong>
                <br>@ {{$user->user_id}}
            </p>
            <button id="follow_btn"> @if($follow_is) フォローをはずす @else フォローする@endif</button>
            <p>自己紹介</p>
            <p>{{$user->body}}</p>
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
        <!-- <form action="" method="get" class="col-md-12">
            <div class="input-group" id="serach_bar"> <input type="text" class="form-control" name="serach_text" placeholder="" value="{$search_text}">
                <div class="input-group-append"> <input class="btn btn-primary" type="submit" name="serach" value="検索"> </div>
            </div>
        </form> -->
    </div>
</div> 
@endsection
@section('script')
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function(){
    var follow = @if ($follow_is) true @else false @endif;
    // Ajax button click
    $('#follow_btn').on('click',function(){
        $.ajax({
            url:'/follow',
            type:'POST',
            data:{
                'follow':{{$auth->id}},
                'follower':{{$user->id}},
                'follow_is':follow,
            }
        })

        // Ajaxリクエストが成功した時発動
        .done( (data) => {
            $('.result').html(data);
            console.log(data);
            //console.log(follow);
        })
        // Ajaxリクエストが失敗した時発動
        .fail( (data) => {
            $('.result').html(data);
            console.log(data);
        })
        // Ajaxリクエストが成功・失敗どちらでも発動
        .always( (data) => {

        });

        if(follow){
            $('#follow_btn').text("フォローする");
            follow = false;
        }else{
            $('#follow_btn').text("フォローをはずす");
            follow = true;     
        }
    });
});

</script>

@endsection
