<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/user_cart.css" />
                <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js">
                </script>
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
            var tmp = $('#input-num' + id).val();
            $('#input-num' + id).val(parseInt(tmp) + 1);
            var count = $('#input-num' + id).val();
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
               $('#input-num' + id).val(1);
            } else {
              $('#input-num' + id).val(count - 1);
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

         function select_address(id) {
            $.post('<?php echo base_url() ?>good/delete_cart_item', {'cart_id':id}, function(data, status){
                        if ('success' == status) {
                            alert(data);
                            location.href="<?php echo base_url() ?>user/cart_list";
                        } else {
                            alert("删除购物车失败");
                        }
            });
        }


        $(function() {
              //init
              $(".cart_delete").hide();

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

              $("#edit_cart").click(function() {
                    $(".cart_delete").toggle();
              });

               $("#go_pay").click(function() {
                    var vals = [];
                    $('input:checkbox:checked').each(function (index, item) {
                         vals.push($(this).val());
                     });
                    
                     $.post('<?php echo base_url()?>order/create_order', {"carts":vals}, function(data, status){
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
             <div id="user_name">
              <a href="<?php echo base_url() ?>user/user_center">
              <?php echo get_data_from_cookie('username'); ?>
              </a>
              <br/>
              <br/>
              </div>
              <div class = "select_address">
                    <table id="address_item" onclick="select_address(<?php echo $item['id']?>)">
                              <tr>
                                <td>
                                  收件人: <?php echo $item['name']?>
                                </td>
                                  <td>
                                  手机号码: <?php echo $item['phone']?>
                                </td>
                                <td><a href="#" class="cart_delete" onclick="delete_cart_item(<?php echo $item['id']?>)">删除</a></td>
                              </tr>
                              <tr>
                                  <td id="address_detail"><?php echo $item['province']; ?> <?php echo $item['city']; echo $item['address_detail']?></td>
                                  <td>邮编：<?php echo $item['zip']; ?></td>
                              </tr>
                        </table>  
              </div>
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
                    <div id="order_list">
                         <?php foreach ($orders as $item):?>
                         <table id="table_demand" onclick="item_detail(<?php echo $item['id']?>, <?php echo $item['type']?>)">
                                <tr rowspan='4'><input type='checkbox' value='<?php echo $item['id']?>' name="cartids"/></tr>
                                <tr>
                                    <tr id="header_part">
                                        <td id="demand_title">标题：<?php echo $item['title']; ?></td>
                                    </tr>
                                    <tr id="description_part">
                                        <td>描述：<?php echo $item['description']; ?></td>
                                    </tr>
                                     <tr id="footer_part">
                                        <td id="demand_price">工期：<?php echo $item['pro_time']; ?></td>
                                        <td id="demand_time">时间: <?php echo $item['public_time']; ?></td>
                                    </tr>
                                </tr>
                          </table>  
                        <?php endforeach;?>
                    </div>
                <div id="operation_list">
                    <a id="go_pay" href="#">提交付款</a>
                </div>
        </main>
    </div>
</body>
</html>