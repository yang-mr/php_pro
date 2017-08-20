<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/user_cart.css" />
                <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js">
                </script>
                <script language="JavaScript" src="<?php echo base_url() ?>public/js/user_center.js"></script>
    <title>购物车</title>
    <script>
        function jss_delete(id) {
            $.get("./delete_message/" + id, function(data, status){
                if (status == 'success') {
                     alert(data);
                     location.href = 'user_center';
                } else {
                     alert('删除失败');
                }
            });
        }

        function num_jia(id) {
            var tmp = $('#input-num').val();
            $('#input-num').val(parseInt(tmp) + 1);
            var count = $('#input-num').val();
              $.post('<?php echo base_url() ?>good/change_cart_count', {"count":count, 'cart_id':id}, function(data, status){
                        if ('success' == status) {
                            alert(data);
                        } else {
                            alert("增加数量失败");
                        }
            });
        }

         function num_jian(id) {
            var count = $('#input-num' + id).val();
            if(count <= 1) {
               $('#input-num').val(1);
            } else {
              $('#input-num').val(count - 1);
            }

            var count = $('#input-num' + id).val();
            $.post('<?php echo base_url() ?>good/change_cart_count', {"count":count, 'cart_id':id}, function(data, status){
                        if ('success' == status) {
                            alert(data);
                          //  location.href="./user_center";
                        } else {
                            alert("减少数量失败");
                        }
            });
        }

        $(function() {
              $("#submit").click(function() {
                    var title = $("#title").val();
                    var description = $("#description").val();
                    var price = $("#price").val();
                    var area = $("#area").val();

                    $.post('./post_message', {"title":title, "description":description, "price":price, "area":area}, function(data, status){
                        if ('success' == status) {
                            alert(data);
                            location.href="./user_center";
                        } else {
                            alert("发布失败");
                        }
                    });
            });
        });
 </script>
</head>
<body>
    <header>
        <?php if (is_login()) {?>
             <div id="user_name">
              <a href="<?php echo base_url() ?>user/user_center">
              <?php echo get_data_from_cookie('username'); ?></a>
              <a href="#" id='post_message'>
              <?php if(get_data_from_cookie('type') == 0) {?> 发布新需求<?php } else {?>
              发布新作品<?php }?> </a>
            </div>
       <?php } else {?>
                <a href="<?php echo base_url()?>user/login">登陆</a>
                <a href="<?php echo base_url()?>user/register">注册</a>
       <?php }?>
    </header>
    <div id="context">
        <nav>
            <ul>
                <li>我的订单</li>
                <li>我的订单</li>
                <li>我的订单</li>
                <li>我的订单</li>
            </ul>
        </nav>
        <main>
                <div id="demands_content_main">
                    <div id="content_header"></div>
                    <div id="demands_content">
                         <?php foreach ($carts as $item):?>
                         <table id="table_demand" onclick="item_detail(<?php echo $item['id']?>, <?php echo $item['type']?>)">
                                <tr rowspan='4'><input type='checkbox' value='<?php echo $item['id']?>' name="cartids"/></tr>
                                <tr>
                                    <tr id="header_part">
                                        <td id="demand_title">标题：<?php echo $item['title']; ?></td>
                                        <td><a href="#">删除</a></td>
                                    </tr>
                                    <tr id="description_part">
                                        <td>描述：<?php echo $item['description']; ?></td>
                                    </tr>
                                     <tr id="footer_part">
                                        <td id="demand_price">工期：<?php echo $item['pro_time']; ?></td>
                                        <td id="demand_time">时间: <?php echo $item['public_time']; ?></td>
                                    </tr>
                                </tr>
                                 <tr rowspan='3'>
                　　　　    <ul class="btn-numbox">
                                    <li><span class="number">数量</span></li>
                                    <li>
                                    <ul class="count">
                                        <li><span id="num-jian" class="num-jian" onclick='num_jian(<?php echo $item['cart_id']?>)'>-</span></li>
                                        <li><input type="text" class="input-num" id="input-num<?php echo $item['cart_id']?>" value="<?php echo $item['number']?>" /></li>
                                        <li><span id="num-jia" class="num-jia" onclick='num_jia(<?php echo $item['cart_id']?>)'>+</span></li>
                                    </ul>
                                    </li>
                                    <li><span class="kucun">（库存:54）</span></li>
                        　　　  </ul>
                     </tr>
                          </table>  
                        <?php endforeach;?>
                    </div>
                    <div id="content_footer">
                        <?php echo $pages?>
                    </div>
                </div>
        </main>
    </div>
</body>
</html>