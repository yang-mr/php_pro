@extends('layouts.app')

@section('content')
<link href="{{ asset('/css/user_desc.css') }}" rel="stylesheet" type="text/css">
<div id="content">
    <nav id="nav_left">
        <div class="inner">
              <img src="{{ $img_avatar }}"/>
                         <p>{{ $name }} {{ $sex }}</p> 
                         <p>{{ $city }} {{ $area }}</p> 
                        <div>
                            @if( $attention)
                            <a href="../send_email/{{ $id }}">写信</a>
                            <button id='bt_attention' onClick="bt_attention( {{ $attention }})">取消关注</button>
                            @else 
                             <button id='bt_attention' onClick="bt_attention( {{ $attention }})">添加关注</button>
                            @endif
                            <a href="../send_email/{{ $id }}">写信</a>
                             <a href="../send_email/{{ $id }}">送礼物</a>
                        </div>
                    </div>
        </div>
    </nav>
     <div class="user_div">
</div>
@endsection


