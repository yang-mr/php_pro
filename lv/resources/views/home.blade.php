@extends('layouts.app')

@section('content')
<div class="container">
	<header>
		<ul>
			<li><a href="./index">首页</a></li>
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
        <div class="handle_env">
        	<a href="#">关注过我的人</a>
        	<a href="#">看过我的人</a>
        </div>
    </nav>
    <div></div>
</div>
@endsection
