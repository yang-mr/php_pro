@extends('layouts.app')

@section('content')
<link href="{{ asset('/css/index.css') }}" rel="stylesheet" type="text/css">
<div id="content">
    <nav id="nav_left">
        <div class="inner">
            fdfd
        </div>
    </nav>
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
</div>
@endsection
