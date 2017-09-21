@extends('layouts.auto_app')

<link href="{{ asset('/css/gift.css') }}" rel="stylesheet" type="text/css">
<script src="http://www.jq22.com/jquery/jquery-1.10.2.js"></script>
    <script type="text/javascript">
     var w,h,className, type = 0;
    function getSrceenWH(){
      w = $(window).width();
      h = $(window).height();
      $('#dialogBg').width(w).height(h);
    }

    window.onresize = function(){  
      getSrceenWH();
    }  
    $(window).resize();  

        $(function(){
           getSrceenWH();
          $('.bounceIn').click(function(){
            className = $(this).attr('class');
           // alert(className);
            $('#dialogBg').fadeIn(300);
            $('#dialog').removeAttr('class').addClass('animated bounceIn').fadeIn();
          });
          
          $('.claseDialogBtn').click(function(){
            $('#dialogBg').fadeOut(300,function(){
              $('#dialog').addClass('bounceOutUp').fadeOut();
            });
          });

        $("#gift_type li").click(function() {
                                   // 添加当前元素的样式
          /*  $(this).siblings('li').removeClass('selected');  // 删除其他兄弟元素的样式
            $(this).addClass('selected'); */
        });
       // $('#gift_type').children(':first').addClass('selected');
        $('#gift_type').children('li').eq({{ $type }}).addClass('selected');
        $('#my_type').children('li').eq({{ $type }}).addClass('selected');
       });

        function getGifts(path) {
            /* $.ajax({
                cache: true,
                type: "GET",
                url:path,
                async: false,
                dataType: 'json',
                error: function(request) {
                  alert('失败');
                },
                success: function(data) {
                 $("#gift_content").html(data.data);

             //    alert(data.gifts.data.length);
                }
            });*/
        }

        var gift_id = 0;
        function giveGift(path) {
             $('#dialogBg').fadeIn(300);
             $('#dialog').removeAttr('class').addClass('animated bounceInDown').fadeIn();
            $.ajax({
                        cache: true,
                        type: "GET",
                        url:path,
                        async: false,
                        dataType: 'json',
                        error: function(request) {
                            alert('收藏失败');
                        },
                        success: function(data) {
                            this.gift_id = data.data.gift_id;
                            console.log(data.data.gift_id);
                             var dataObj = data.data, //返回的result为json格式的数据
                         con = "";
                         $.each(dataObj, function(index, item){
                            var id = item.user_id;
                            console.log(id);
                            con += "<div class='item' onClick='goGive(" + id + ")'>";
                            con += "<img src=\"" + '{{ asset('img/default_avatar.png') }}' + "\"/>"; 
                            con += "<div>姓名："+item.name+item.sex +"</div>";
                            con += "</div>"
                        });
                       console.log(con);    //可以在控制台打印一下看看，这是拼起来的标签和数据
                        $("#attention_users").html(con); //把内容入到这个div中即完成
                       //   $('#attention_users').html(data);
                        }
                    });
        }

        function goGive(id) {
            alert(id);
            alert(this.gift_id);
        }

        function collectGift(obj, path) {
           // obj=$(this);//回调函数前先写入变量; 
            if ($(obj).text() === '收藏') {  
                $.ajax({
                    cache: true,
                    type: "GET",
                    url:path,
                    async: false,
                    dataType: 'json',
                    error: function(request) {
                        alert('收藏失败');
                    },
                    success: function(data) {
                      if (data == 1) {
                        $(obj).text('已收藏');
                        $(obj).removeAttr("href");
                        $(obj).removeClass('item_operate a');
                        $(obj).addClass('gift_selected');
                      } else if (data == 0) {
                        alert('收藏失败');
                      }
                    }
                });
            } 
        }
    </script>
@section('left_content')
    <ul id="gift_type">
        <li class="list_li" onClick="getGifts('{{ route('gift_type', 0) }}')"><a href="{{ route('gift_type', 0) }}">我收到的信件</a></li>
        <li class="list_li" onClick="getGifts('{{ route('gift_type', 1) }}')"><a href="{{ route('gift_type', 1) }}">我发送的信件</a></li>
    </ul>
@endsection

@section('content')
    <div id="gift_content">
        <div id="gift_items">
           @foreach($letters as $letter)
           <div class = "gift_item">
              <img src="{{ asset('img/default_avatar.png')}}" />
              <div class="item_title">
                  {{ $gift['title']}} <em>{{ $gift['price'] }}</em>
              </div>
              @if ($display)
              <div class="item_operate">
                <a href="#" onClick="giveGift('{{ route('gift_attention', $gift['id']) }}')">赠送</a> 
                @if ($gift['collect'])
                  <em>已收藏</em>
                @else
                  <a href="javascript:;" onClick="collectGift(this, '{{ route('gift_collect', $gift['id']) }}')">收藏</a>
                @endif
              </div>
              @endif
           </div>
          @endForeach
        </div>
        <div class="link">
            {{ $data->links() }}
        </div>
    </div>
@endsection

@section('right_content')
    <div id="dialogBg"></div>
              <div id="dialog" class="animated">
              <img class="dialogIco" width="50" height="50" src="{{ asset('img/ico.png') }}" alt="" />
              <div class="dialogTop">
                <a href="javascript:;" class="claseDialogBtn">关闭</a>
              </div>
              <p>赠送给我关注的人</p>
              <div id="attention_users">
                  
              </div>
            </div>
@endsection

 