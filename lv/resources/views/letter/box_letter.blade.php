@extends('layouts.auto_app')

<link href="{{ asset('/css/box.css') }}" rel="stylesheet" type="text/css">
<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
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
        $('#my_type').children('li').eq({{ $type }}).addClass('selected');
       });

        function lookLetter(path) {
            $.ajax({
                cache: false,
                type: "GET",
                url:path,
                async: false,
                processData: false,  
                dataType: 'json',
                 error: function(request) {
                  alert('发送失败');
                },
                success: function(data) {
                  if (data.status == 1) {
                    //查看成功
                    $('#attention_users').text(data.content);
                    $('#dialogBg').fadeIn(300);
                    $('#dialog').removeAttr('class').addClass('animated bounceIn').fadeIn();
                  } else if (data == 0) {
                    alert("查看失败");
                  } else if (data == 2) {
                    alert('开通vip');
                  }
                }
            });
        }

        function set_status(obj, path) {
            var o = obj;
            var hint = $(o).text().trim();
            alert(hint);
            if (hint == '设为已读') {
                  $.ajax({
                cache: false,
                type: "GET",
                url:path,
                async: false,
                processData: false,  
                dataType: 'json',
                 error: function(request) {
                  alert('设置失败');
                },
                success: function(data) {
                  if (data == 1) {
                    //查看成功
                    $(o).text('已读')
                  } else if (data == 0) {
                    alert("设置失败");
                  }
                }
                });
            }
        }
    </script>
@section('left_content')
    <ul id="my_type">
        <li class="list_li"><a href="{{ route('in_letter') }}">我发送的信件</a></li>
        <li class="list_li"><a href="{{ route('out_letter') }}">我收到的信件</a></li>
    </ul>
@endsection

@section('content')
    <div id="gift_content">
        <div id="gift_items">
           @if (count($letters) == 0)
                @if ($type == 0)
                    <p>暂无发件过</p>
                @else 
                    <p>暂无收到件</p>
                @endif
            @else
               @foreach($letters as $letter)
               <div class = "letter_item">
                <img src="{{ asset('img/default_avatar.png')}}" />
                <div class="img_right">
                    <p>
                        {{$letter['letter_id']}}
                        <strong>{{$letter['created_at']}}</strong> 
                    @if ($letter['user']['status'] == 0)
                    <em class="letter_status">对方还没查看该信件</em>
                    @elseif ($letter['user']['status'] == 1)
                    <em class="letter_status">对方已经查看该信件</em>
                    @endif
                    </p>
                  
                  <div class="item_title">
                      {{ $letter['user']['name']}} <em>{{ $letter['user']['city'] }}</em>
                       <div class="item_operate">
                        <a href="#">删除</a> 
                        <a href="javascript:;" onClick="lookLetter('{{ route('look_letter', $letter['letter_id']) }}')">查看信件内容</a>
                        @if ($type == 1) 
                        <a href="javascript:;" onClick="set_status(this, '{{ route('set_status', $letter['letter_id'])}}')">
                             @if ($letter['status'] == 0)
                             设为已读
                             @else 
                             已读
                             @endif
                        </a>
                            
                        @endif
                  </div>
                  </div>
                 
                </div>
                </div>
              @endForeach
            @endif
        </div>
        <div class="link">
           
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
              <div id="attention_users">
                  
              </div>
            </div>
@endsection

 