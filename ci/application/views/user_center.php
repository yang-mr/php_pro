<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/user_center.css" />
                <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js">
                </script>
    <link href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css" rel="stylesheet">
                <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js">
                </script>
                                    <script language="JavaScript" src="<?php echo base_url() ?>public/js/user_center.js"></script>
                                    <script src="https://cdn.ronghub.com/RongIMLib-2.2.7.min.js"></script> 
    <title>个人中心</title>
    <script>
        function startInit(){
            var ak = "?php echo $this->config->item('ry_app_key');?>";
            var params = {
                appKey : ak,
                token : "<?php echo get_data_from_cookie('ry_token')?>",
                navi : ''
            };
            var userId = "";
            var callbacks = {
                getInstance : function(instance){
                    RongIMLib.RongIMEmoji.init();
                    //instance.sendMessage
                },
                getCurrentUser : function(userInfo){
                    console.log(userInfo.userId);
                    userId = userInfo.userId;
                    alert("链接成功；userid=" + userInfo.userId);
                },
                receiveNewMessage : function(message){
                    //判断是否有 @ 自己的消息
                    var mentionedInfo = message.content.mentionedInfo || {};
                    var ids = mentionedInfo.userIdList || [];
                    for(var i=0; i < ids.length; i++){
                        if( ids[i] == userId){
                            alert("有人 @ 了你！");
                        }
                    }
                    showResult("show1",message);
                    messageOutput(message);
                }
            };
            init(params,callbacks);
        }

        function getValue(id){
            return document.getElementById(id).value;
        }

        function jss_delete(id) {
            $.get("<?php echo base_url() ?>user/delete_message/" + id, function(data, status){
                if (status == 'success') {
                     alert(data);
                     location.href = '<?php echo base_url()?>user/user_center';
                } else {
                     alert('删除失败');
                }
            });
        }

        $(function() {
              $("#submit").click(function() {
                    var title = $("#title").val();
                    var description = $("#description").val();
                    var price = $("#price").val();
                    var area = $("#area").val();

                    $.post('<?php echo base_url() ?>user/post_message', {"title":title, "description":description, "price":price, "area":area}, function(data, status){
                        if ('success' == status) {
                            alert(data);
                            location.href="<?php echo base_url()?>user/user_center";
                        } else {
                            alert("发布失败");
                        }
                    });
            });

              $("#submit_2").click(function() {
                    var form = new FormData(document.getElementById('worker'));
                      $.ajax({
                        url:"<?php echo base_url()?>user/post_message",
                        type:"post",
                        data:form,
                        processData:false,
                        contentType:false,
                        success:function(data){
                            if (data == '发布成功') {
                                alert("发布成功");
                                location.href="<?php echo base_url()?>user/user_center";
                            } else {
                                alert("发布失败");
                            }
                        },
                        error:function(e){
                            alert("错误！！");
                        }
                    });        
              });
        });
 </script>
</head>
<body>
    <?php echo validation_errors()?>
 <div id="dialog-form" title="发布新需求">
  <p class="validateTips">所有的表单字段都是必填的。</p>
    <label for="name">标题</label>
    <input type="text" id="title" class="text ui-widget-content ui-corner-all">
    <label for="email">描述</label>
    <input type="text" id="description" value="" class="text ui-widget-content ui-corner-all">
    <label for="password">预算</label>
    <input type="text" id="price" value="" class="text ui-widget-content ui-corner-all">
     <label for="password">面积</label>
    <input type="text" id="area" value="" class="text ui-widget-content ui-corner-all">
    <button id="submit" class="text ui-widget-content ui-corner-all">发布新需求</button>
</div>

<div id="dialog-form-worker-designer" title="添加项目简介">
  <p class="validateTips">所有的表单字段都是必填的。</p>
  <form id="worker">
    <label for="name">标题</label>
    <input type="text" name="title" id="name" class="text ui-widget-content ui-corner-all">
    <label for="email">描述</label>
    <input type="text" name="description" id="email" value="" class="text ui-widget-content ui-corner-all">
    <label for="password">项目时间</label>
    <input type="text" name="pro_time" value="" class="text ui-widget-content ui-corner-all">
    <label for="password">上传项目图片或者文件</label>
    <input type="file" name="userfile[]" value="" class="text ui-widget-content ui-corner-all">
    <input type="file" name="userfile[]" value="" class="text ui-widget-content ui-corner-all">
    <input type="file" name="userfile[]" value="" class="text ui-widget-content ui-corner-all">
    <input type="file" name="userfile[]" value="" class="text ui-widget-content ui-corner-all">
    <input type="file" name="userfile[]" value="" class="text ui-widget-content ui-corner-all">
    <input type="button" value="发布新需求" id="submit_2"/>
    </form>
</div>
    <header>
        <p id="user_name"> <?php echo get_data_from_cookie('username'); ?><a href="<?php echo base_url()?>user/logout">退出登录</a><a href="<?php echo base_url()?>home/index">首页</a><a href="#" id='post_message'><?php if(get_data_from_cookie('type') == 0) {?>
        发布新需求
        <?php } else {?>
            发布新作品
        <?php }?>
        </a></p>
        <button onclick="startInit()">我的消息</button>
    </header>
    <div id="context">
        <nav>
            <ul>
                <li><a href="<?php echo base_url()?>user/cart_list">我的购物车</a></li>
                <li><a href="<?php echo base_url()?>order/order_list">我的订单</a></li>
                <li><a href="<?php echo base_url()?>user/user_address">我的收货地址</a></li>
            </ul>
        </nav>
        <main>
                <?php if($_COOKIE['type'] == 0) {?>
                <?php foreach ($demands as $item):?>
                     <table id="table_demand">
                    <tr id="header_part">
                        <th id="demand_title">标题：<?php echo $item['title']; ?></th>
                        <th id="demand_area">面积: <?php echo $item['area']; ?></th>
                    </tr>
                    <tr id="description_part">
                        <th>装修描述：<?php echo $item['description']; ?></th>
                        <th><a href="<?php echo base_url()?>user/update_message/<?php echo $item['demand_id']?>">编辑</a></th>
                        <th><a href="javascript:void(0);" onclick="jss_delete(<?php echo $item['demand_id']?>)">删除</a></th>
                    </tr>
                     <tr id="footer_part">
                        <th id="demand_price">预算：<?php echo $item['price']; ?></th>
                        <th id="demand_time">发布时间: <?php echo $item['public_date']; ?></th>
                    </tr>
                      </table>  
                <?php endforeach;} else {?>
                     <?php foreach ($demands as $item):?>
                     <table id="table_demand">
                    <tr id="header_part">
                        <th id="demand_title">标题：<?php echo $item['title']; ?></th>
                        <th><a class="edit" href="<?php echo base_url()?>user/update_message/<?php echo $item['id']?>">编辑</a></th>
                        <th><a class="delete" href="<?php echo base_url()?>user/delete_message/<?php echo $item['id']?>">删除</a></th>
                    </tr>
                    <tr id="description_part">
                        <th>项目描述：<?php echo $item['description']; ?></th>
                    </tr>
                     <tr id="footer_part">
                        <th id="demand_price">项目周期：<?php echo $item['pro_time']; ?></th>
                        <th id="demand_time">发布时间: <?php echo $item['public_time']; ?></th>
                    </tr>
                      </table>
                <?php endforeach;?>
                <?php }?>
                <div>
                    <?php echo $pages?> 
                </div>
        </main>
        <aside></aside>
    </div>
    <footer>
    </footer>
</body>
</html>