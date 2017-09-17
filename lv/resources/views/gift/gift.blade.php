@extends('layouts.auto_app')

<link href="{{ asset('/css/gift.css') }}" rel="stylesheet" type="text/css">
<script src="http://www.jq22.com/jquery/jquery-1.10.2.js"></script>
    <script type="text/javascript">
        $(function(){
        $("#gift_type li").click(function() {
                                   // 添加当前元素的样式
            $(this).siblings('li').removeClass('selected');  // 删除其他兄弟元素的样式
            $(this).addClass('selected'); 
    });
        $('#gift_type').children(':first').addClass('selected');
});

        function getGifts(path) {
             $.ajax({
                cache: true,
                type: "GET",
                url:path,
                async: false,
                dataType: 'json',
                error: function(request) {
                  alert('失败');
                },
                success: function(data) {
                 alert(data.gifts.data.length);
                }
            });
        }
    </script>
@section('left_content')
    <ul id="gift_type">
        <li class="list_li" onClick="getGifts('{{ route('gift_type', 0) }}')">全部</li>
    </ul>
@endsection

@section('content')
    <div id="gift_content">
        @foreach($gifts as $gift)
            <div class = "gift_item">
                {{ $gift['title']}}
            </div>
        @endForeach
    </div>
@endsection
