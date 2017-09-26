@extends('layouts.auto_app')
<link href="{{ asset('/css/user_desc.css') }}" rel="stylesheet" type="text/css">
<title>{{ $name }} {{ $home_location }}</title>

<script type="text/javascript">
    $(function() {
        if ('{{ $attention }}' == 'cancel_attention') {
          $('#bt_attention').text('取消关注');
        } else {
          $('#bt_attention').text('添加关注');
        }
    });

    function add_attention(path){
         var hint = $('#bt_attention').text();
         if (hint == '取消关注') {
              $.ajax({
                cache: true,
                type: "get",
                url:path,
               // data:['id':id],// 你的formid
                async: false,
                dataType: 'json',
                error: function(request) {
                  alert(request);

                },
                success: function(data) {
                  if (data == 1) {
                    $('#bt_attention').text('添加关注');
                  } else if (data == 0) {
                    alert("取消关注失败");
                  }
                }
              });
         } else if (hint == '添加关注') {
             $.ajax({
                cache: true,
                type: "get",
                url:path,
               // data:['id':id],// 你的formid
                async: false,
                dataType: 'json',
                error: function(request) {
                  alert(request);

                },
                success: function(data) {
                  if (data == 1) {
                    //提交成功
                    $('#bt_attention').text('取消关注');
                    //alert("提交成功");
                  } else if (data == 0) {
                    alert("添加关注失败");
                  }
                }
            });
         }
    }
</script>
@section('left_content')
<nav id="nav_left">
        <div class="inner">
              <img src="{{ $avatar_url or asset('img/default_avatar.png') }}"/>
                         <p>{{ $name }} {{ $sex }}</p> 
                         <p>{{ $city }} {{ $area }}</p> 
                        <div>
                            <button id='bt_attention' onClick="add_attention('{{ route($attention, $id) }}')">添加关注</button>
                            <a href="{{ route('write_letter', $id) }}">写信</a>
                             <a href="../send_email/{{ $id }}">送礼物</a>
                        </div>
                    </div>
        </div>
    </nav>
@endsection

@section('content')

<div class="content">
    
</div>
@endsection


