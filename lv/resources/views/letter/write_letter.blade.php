@extends('layouts.auto_app')

<link href="{{ asset('/css/write_letter.css') }}" rel="stylesheet" type="text/css">
<script type="text/javascript">
	// 
	var num = 0;

	function select_letter_model() {
		var n = 0, m = {{ count($models) }} - 1;
		var c = m-n+1; 
    	this.num = Math.floor(Math.random() * c + n);
		$('#text_letter').val('');
		var content = '{{ $models[this.num]['content']}}';
		$('#text_letter').val(content);
	}
</script>
@section('left_content')
    <nav id="nav_left">
        <div class="inner">
        	fd
        </div>
    </nav>
@endsection

@section('content')
    <div class="letter_content">
    	<div class="user_desc">
    		<img src="{{ asset('img/default_avatar.png') }}" />
    		<div class="img_right">
    			<div class="div_name">
    			<strong>{{$user['name']}}</strong>{{ $user['sex'] }}
	    		</div>
	    		<div class="div_description">
	    			<strong>{{$user['city']}}</strong>{{ $user['area'] }}
	    		</div>
    		</div>
    	</div>
    	<p class="p_hint"> 安全提示：为了保护您的隐私，避免骚扰，请您不要在初次联系时留下微信、QQ和手机号，您依然可以给有过沟通的用户留下联系方式。    交友安全提示 </p>
    	<div class="letter">
    		<div class="letter_top">
    			<div class="letter_top_left">
    			<a href="#">使用模块</a>
    			<a href="javascript:;" onClick="select_letter_model()">换一句</a>
    			</div>
    			<div class="letter_top_right">
    			<a href="#">如果不能正常发信?</a>
    			</div>
    		</div>
    		<div class="letter_form">
    			<form id="letter_form">
                {{ csrf_field() }}
                  <textarea name="letter_content" id="text_letter"></textarea>
                  <input type="button" value="发送" class="submitBtn" onClick="addOrUpdate()" />
              </form>
    		</div>
    	</div>
	</div>
@endsection
@section('right_content')
    <div class="content">
       
</div>
@endsection
