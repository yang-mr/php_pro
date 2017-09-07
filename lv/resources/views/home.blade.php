@extends('layouts.app')

@section('content')
<div class="container">
	<header>
		<ul>
			<li><a href="#">1111</a></li>
			<li><a href="#">1111</a></li>
			<li><a href="#">1111</a></li>
			<li><a href="#">1111</a></li>
			<li><a href="#">1111</a></li>
		</ul>
	</header>
    <nav id="nav">
        <img src="https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=2723378661,754487282&fm=85&s=3C96489608749FCC610B4AAE0300700E"/>
        <div class="user_description">
        	<p>{{$user_description}}</p>
        </div>
        <a href="./edit_msg">编辑资料</a>
        <div class="handle_env">
        	<a href="#">关注过我的人</a>
        	<a href="#">看过我的人</a>
        </div>
    </nav>
    <div></div>
</div>
@endsection
