@extends('base.base') 

@section('head')
<link rel="stylesheet" type="text/css" href="/css/profile_edit.css">
@endsection

@section('content')
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <form action="{{ route('profile_edit') }}" method="post" enctype="multipart/form-data" id="content">
            @csrf
            <h2>プロフィール編集</h2>
            <div class="form-group row"> 
                <label for="user_name" class="col-sm-2 col-form-label">ユーザー名</label>
                <div class="col-sm-10"> 
                    <input type="text" class="form-control" name="user_name" id="user_name" maxlength='80' placeholder="ユーザー名" value="@if($errors->any()){{ old('user_name')}}@else{{$auth->name}}@endif"> 
                </div>
                @if ($errors->has('user_name'))
                    <span class="alert alert-danger col-md-11">
                        <strong>{{ $errors->first('user_name') }}</strong>
                    </span>
                    <br>
                @endif
            </div>
            <div class="form-group row"> 
                <label for="user_body" class="col-sm-2 col-form-label">自己紹介</label>
                <div class="col-sm-10"> 
                    <textarea name="user_body" id="" class="form-control" cols="30" rows="10">@if($errors->any()){{ old('user_body')}}@else{{$auth->body}}@endif</textarea>
                </div>
                @if ($errors->has('user_body'))
                    <span class="alert alert-danger col-md-11">
                        <strong>{{ $errors->first('user_body') }}</strong>
                    </span>
                    <br>
                @endif
            </div>
            <div class="form-group row"> 
                <label for="user_img" class="col-sm-2 col-form-label">プロフィール画像</label>
                <div class="col-sm-10"> 
                    <input type="file" name="user_img" class="" accept=".jpg,.gif,.png,image/gif,image/jpeg,image/png"> 
                </div>
                @if ($errors->has('user_img'))
                    <span class="alert alert-danger col-md-11">
                        <strong>{{ $errors->first('user_img') }}</strong>
                    </span>
                    <br>
                @endif
            </div>
            <div class="float-right"> <input type="submit" class=" float-right btn btn-primary" id="login" name="edit" value="変更"> 
            </div> 
        </form>
    </div>
    <div class="col-md-2"></div>
</div>
@endsection
