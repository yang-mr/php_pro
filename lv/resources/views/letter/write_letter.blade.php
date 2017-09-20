@extends('layouts.auto_app')

@section('left_content')
    <nav id="nav_left">
        <div class="inner">
        	fd
        </div>
    </nav>
@endsection

@section('content')

<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>

<script type="text/javascript">
    jQuery(function() {
        $('.submitBtn').click(function() {
            $.ajax({
                cache: false,
                type: "POST",
                url:"{{ route('insert_letter') }}",
                data:$('#letter_form').serialize(),// 你的formid
                async: false,
                processData: false,  
                dataType: 'json',
                 error: function(request) {
                  alert('发送失败');
                },
                success: function(data) {
                  if (data == 1) {
                    //提交成功
                    alert("发送成功");
                    window.history.back();
                  } else if (data == 0) {
                    alert("发送失败");
                  }
                }
            });
        });
    });
</script>
<link href="{{ asset('/css/write_letter.css') }}" rel="stylesheet" type="text/css">

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
                  <textarea name="letter_content" id="text_letter" required></textarea>
                   <input type="hidden" value="{{ $user['id'] }}" name="id" />
                  <input type="button" value="发送" class="submitBtn" />
              </form>
    		</div>
    	</div>
	</div>
@endsection
@section('right_content')
    <div class="content">
        
    </div>
@endsection
