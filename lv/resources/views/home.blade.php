@extends('layouts.app')

@section('content')
<div class="container">
	<header>
		<ul>
			<li><a href="../public">首页</a></li>
			<li><a href="./home" id="person_center">个人中心</a></li>
			<li><a href="#">1111</a></li>
			<li><a href="#">1111</a></li>
			<li><a href="#">1111</a></li>
		</ul>
	</header>
    <nav id="nav">
        <a href="./home/edit_msg"><img src="{{ $user_avatar }}" onClick="go_personcenter()"/></a>
        <div class="user_description">
        	<p>{{$user_description}}</p>
        </div>
    </nav>
    <div id="content">
         <div class="handle_env">
            <a href="#">我关注的人</a>
            @if (count($myAttentions) === 0)
                暂无
            @else
                 @foreach ($myAttentions as $attention)
                    <div onClick="">
                        <img src="{{ $attention['img_avatar'] }}" />
                        <p>用户名: {{ $attention['name'] }}</p>
                    </div>
                @endforeach
            @endif
        </div>
        <div>
              <a href="#">我看过的人</a>
               @if (count($myLooks) === 0)
                暂无
               @else
                     @foreach ($myLooks as $look)
                    <img src="{{ $look['img_avatar'] }}" />
                    <p>用户名: {{ $look['name'] }}</p>
                    @endforeach
                @endif
        </div>
    </div>
</div>
@endsection
