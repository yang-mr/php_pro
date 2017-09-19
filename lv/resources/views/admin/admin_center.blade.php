<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">  
<meta name="apple-mobile-web-app-status-bar-style" content="black">  
<meta content="telephone=no" name="format-detection">

    <title>管理员中心</title>

    <!-- Styles -->
    <link href="{{ asset('css/admin_center.css') }}" rel="stylesheet">
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

    });

    function addOrUpdate() {
      alert(type);
      if (type == 0) {
        var formData = new FormData($('#editForm')[0]);
        //添加gift
         $.ajax({
                cache: false,
                type: "POST",
                url:"{{ route('admin_add_gift') }}",
                data:formData,// 你的formid
                async: false,
                processData: false,  
                dataType: 'json',
                contentType: false,  
                error: function(request) {
                  alert('添加失败');
                },
                success: function(data) {
                  if (data.status == 1) {
                    //提交成功
                    $('#dialogBg').hide();
                    $('#dialog').hide();
                    window.location.reload();  //刷新当前界面
                    alert("提交成功");
                  } else if (data.status == 0) {
                    alert("提交失败");
                  } else if (data.status == 2) {
                    //补全资料
                    alert("补齐资料");
                  }
                   // var json = eval("(" + data + ")");
                 /*  var json = $.parseJSON(data);
                    alert(json.msg);*/
                   /* $.each(json, function (index) {  
                        //循环获取数据    
                        var Id = json[index].msg;  
                    });  */
                   
                  
                    //$("#commonLayout_appcreshi").parent().html(data);
                }
            });
      } else if (type == 1) {
        //修改gift
         $.ajax({
                cache: true,
                type: "POST",
                url:"{{ route('admin_edit_gift') }}",
                data:$('#editForm').serialize(),// 你的formid
                async: false,
                dataType: 'json',
                error: function(request) {
                  alert('修改失败');
                },
                success: function(data) {
                  if (data.status == 1) {
                    //提交成功
                    $('#dialogBg').hide();
                    $('#dialog').hide();
                    window.location.reload();  //刷新当前界面
                    alert("修改成功");
                  } else if (data.status == 0) {
                    alert("修改失败");
                  } else if (data.status == 2) {
                    //补全资料
                    alert("补齐资料");
                  }
                }
            });
      }
    }

    function gift_edit(gift) {
       this.type = 1;
       $('#gift_id').val(gift['id']);
       $('#gift_title').val(gift['title']);
       $('#gift_description').val(gift['description']);
       $('#gift_price').val(gift['price']);
       $('#gift_discount').val(gift['discount']);
       var type = gift['type'];
       $('#gift_type').val(type);

       $('#dialogBg').fadeIn(300);
       $('#dialog').removeAttr('class').addClass('animated bounceInDown').fadeIn();
    }
    </script>
</head>
<body>
    <div id="app">
        <header>
            <div class="inner">
                <p>管理员中心</p>
                <a href="./logout">退出</a>
            </div>            
        </header>
        <nav>
           <div class="inner">
               <ul>
                   <li><a href="{{ route('admin_center') }}">用户管理</a></li>
                   <li><a href="{{ route('admin_vip') }}">VIP管理</a></li>
                   <li><a href="{{ route('admin_gift') }}">GIFT管理</a></li>
               </ul>
           </div>
        </nav>
        <div id="content">
            @if(isset($users))
            <div id="content_users">
                @foreach ($users as $user)
                  <div class="user" onclick="lookUserDesc({{ $user->id }})">
                      <img src="{{ $user->avatar_url or asset('img/default_avatar.png') }}" />
                      <div class="title_desc">
                        <div class="title">
                          {{ $user->name }} {{ $user->city }} {{ $user->area }}
                        </div>
                        <div class="desc">
                          {{ $user->description }}
                        </div>
                      </div>
                       <div class="admin_operate">
                          <ul> 
                            <li>
                              <a href="#">发短信</a>
                            </li>
                            <li>
                              <a href="#">发邮件</a>
                            </li>
                            <li>
                              <a href="#">赠礼物</a>
                            </li>
                          </ul>
                        </div>
                  </div>
                  <div class="clear"></div>
                @endforeach
                 <div class="pull-right">
                          {{ $users->links() }}
                 </div>
                @endif

            @if (isset($vips))
            <div id="content_vips">
                @foreach ($vips as $vip)
                    <div class="vip">
                        <div>
                          <p>{{ $vip['title'] }}</p>
                          <div class="price_and_discount">
                              {{ $vip['price'] }} {{ $vip['discount'] }}
                          </div>
                          <p>{{ $vip['description'] }}</p>
                        </div>
                    </div>
                @endforeach
                 <div id="pull_right">
                       <div class="pull-right">
                          {{ $vips->links() }}
                       </div>
                 </div>
            </div>
            @endif

            @if (isset($gifts))
            <div><p><a href="javascript:;" class="bounceIn">添加礼物</a></p></div>
            <div id="content_gifts">
                @foreach ($gifts as $gift)
                    <div class="gift">
                        <img src="{{ $gift['img_url'] }}" />
                        <div class="gift_bottom">
                          <p>{{ $gift['title'] }}</p>
                          <div class="price_and_discount">
                              {{ $gift['price'] }} {{ $gift['discount'] }}
                              <div>
                                <a href="javascript:;" onClick="gift_edit({{ $gift }})">编辑</a>
                                <a href="#">删除</a>
                              </div>
                          </div>
                          <p>{{ $gift['description'] }}</p>
                        </div>
                    </div>
                    <div class="clear">
                      
                    </div>
                @endforeach
                 <div id="pull_right">
                       <div class="pull-right">
                          {{ $gifts->links() }}
                       </div>
                 </div>
            </div>
            @endif

              <div id="dialogBg"></div>
              <div id="dialog" class="animated">
              <img class="dialogIco" width="50" height="50" src="{{ asset('img/ico.png') }}" alt="" />
              <div class="dialogTop">
                <a href="javascript:;" class="claseDialogBtn">关闭</a>
              </div>
              <form id="editForm">
                {{ csrf_field() }}
                 <input type="hidden" name="id" value="" class="ipt" id="gift_id" />

                <ul class="editInfos">
                  <li><label><font color="#ff0000">* </font>标题
                  <input type="text" name="title" required value="" class="ipt" id="gift_title" />
                  </label></li>
                  <li><label><font color="#ff0000">* </font>简单表述
                  <textarea name="description" required value="" class="ipt" id="gift_description"></textarea>
                  </label></li>
                  <li><label><font color="#ff0000">* </font>售价
                  <input type="text" name="price" required value="" class="ipt" id="gift_price" />
                  </label></li>

                  <li><label><font color="#ff0000">* </font>折扣
                  <input type="text" name="discount" required value="10" class="ipt" id="gift_discount" />
                  </label></li>
                   <li><label><font color="#ff0000">* </font>描述图片
                  <input type="file" name="img_url" required class="ipt" id="gift_img" />
                  </label></li>
                   <select name="gifttype" id="gift_type">
                       <option value="0">男士礼物</option>
                       <option value="1">女士礼物</option>
                       <option value="2">情人节</option>
                       <option value="3">儿童节</option>
                    </select> 
                  <li><input type="button" value="提交" class="submitBtn" onClick="addOrUpdate()" /></li>
                </ul>
              </form>
             
            </div>
        </div>
    </div>
</body>
</html>

