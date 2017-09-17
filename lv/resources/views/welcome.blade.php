@extends('layouts.auto_app')

<link href="{{ asset('/css/index.css') }}" rel="stylesheet" type="text/css">
@section('left_content')
    <nav id="nav_left">
        <div class="inner">
            fdfd
        </div>
    </nav>
@endsection

@section('content')
    <div class="content">
        <div id="content_area">
             @foreach ($users as $user)
            <a href="{{ route('user_desc', $user['id']) }}">
                <div class="user_div">
                <img src="{{ $user->avatar_url or asset('img/default_avatar.png') }}"/>
                <p>{{ $user->name}} {{ $user->sex }} {{ $user->city}} {{ $user->area}}</p>
                </div>
            </a>
            @endforeach
        </div>
        <div class="paginate">
            {{$users->links()}}
        </div>
</div>
@endsection
@section('right_content')
    <div class="content">
       
</div>
@endsection
