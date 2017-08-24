<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/user_cart.css" />
                <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js">
                </script>
    <title>购物车</title>

    <style type="text/css">
      a:link {
        color:blue;
      }
      a:visited {
      }
      a:hover {
        color:red;
      }
      a:active {
        
      }
    </style>
    <script>
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

         function cancel_order(id) {
            $.get('<?php echo base_url()?>order/cancel_order/' + id, function(data, status){
                        if (status == 'success') {
                          if (data == '取消订单成功') {
                            location.href="<?php echo base_url()?>order/user_order"
                          } else {
                            alter('取消订单失败');
                          }
                        } else {
                          alter('取消订单失败');
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
                    var tmp = vals.join("-");
                    location.href="<?php echo base_url() ?>order/create_order/0/" + tmp;
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
              </div>    
              <a href="#" id='edit_cart'>
            编辑购物车 </a>
    </header>
    <div id="context">
        <nav>
            <ul>
                <li><a href="<?php echo base_url()?>order/user_order/0">待付款</a></li>
                <li><a href="<?php echo base_url()?>order/user_order/1">已付款</a></li>
                <li><a href="<?php echo base_url()?>order/user_order/2">已取消</a></li>
                <li><a href="<?php echo base_url()?>order/user_order/3">已失效</a></li>
            </ul>
        </nav>
        <main>
                <div id="demands_content_main">
                    <div id="content_header"></div>
                    <div id="demands_content">
                         <?php foreach ($orders as $order_item):?>
                         <table id="table_demand")">
                                    <tr id="header_part">
                                        <td id="demand_title">订单：<?php echo $order_item['order_id']; ?></td>
                                        <td id="demand_price">时间：<?php echo $order_item['create_time']; ?></td>
                                        <td id="demand_time">状态: <?php echo $order_item['status']; ?></td>
                                        <td><button onclick="cancel_order(<?php echo $order_item['order_id']?>)">取消订单</button></td>
                                    </tr>
                    <?php foreach ($order_item['carts'] as $cart_item):?>
                         <table id="table_demand")">
                                <tr>
                                    <tr id="header_part">
                                        <td id="demand_title">标题：<?php echo $cart_item['title']; ?></td>
                                    </tr>
                                    <tr id="description_part">
                                        <td>描述：<?php echo $cart_item['description']; ?></td>
                                    </tr>
                                     <tr id="footer_part">
                                        <td id="pro_time">工期：<?php echo $cart_item['pro_time']; ?></td>
                                        <td id="demand_time">数量: <?php echo $cart_item['number']; ?></td>
                                    </tr>
                                </tr>
                          </table>  
                        <?php endforeach;?>
                          </table>  
                          <p><em>金额：<?php echo $order_item['order_money']?></em></p>
                        <?php endforeach;?>
                    </div>
                    <div id="content_footer">
                        <?php echo $pages?>
                    </div>
                </div>
                <?php if (!empty($carts)) {?>
                <div id="operation_list">
                    <button id="go_pay">合并付款</button>
                </div>
                <?php }?>
        </main>
    </div>
</body>
</html>