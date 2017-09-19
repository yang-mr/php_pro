@extends('layouts.app')

@section('content')
<link href="{{ asset('/css/user_desc.css') }}" rel="stylesheet" type="text/css">
<script type="text/javascript">
    function add_attention(path){
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
                    alert(data);
                  if (data.status == 1) {
                    //提交成功
                    alert("提交成功");
                  } else if (data.status == 0) {
                    alert("提交失败");
                  } else if (data.status == 2) {
                    //补全资料
                    alert("补齐资料");
                  }
                }
            });
    }
</script>
<div id="content">
    <nav id="nav_left">
        <div class="inner">
              <img src="{{ $img_avatar }}"/>
                         <p>{{ $name }} {{ $sex }}</p> 
                         <p>{{ $city }} {{ $area }}</p> 
                        <div>
                            @if( $attention)
                            <button id='bt_attention' ">取消关注</button>
                            @else 
                             <button id='bt_attention' onClick="add_attention('{{ route('add_attention', $id) }}')">添加关注</button>
                            @endif
                            <a href="{{ route('write_letter', $id) }}">写信</a>
                             <a href="../send_email/{{ $id }}">送礼物</a>
                        </div>
                    </div>
        </div>
    </nav>
     <div class="user_div">
</div>
@endsection


