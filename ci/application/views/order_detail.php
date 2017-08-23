<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/user_cart.css" />
                <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js">
                </script>
    <title>购物车</title>
    <script>
         function select_address() {
            location.href="<?php echo base_url() ?>user/user_address/0/<?php echo $cartids?>";
        }

        $(function() {
               $("#pay_order").click(function() {
                  location.href="<?php echo base_url()?>order/create_order_ok" + $address['id'] + "/" + $cartids;
              });

                $("#cancel_order").click(function() {
                    $.get('<?php echo base_url()?>order/cancel_order/<?php echo $order_id?>', function(data, status){
                        if (status == 'success') {
                          if (data == '取消订单成功') {
                            
                          } else {
                            alter('取消订单失败');
                          }
                        } else {
                          alter('取消订单失败');
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
         <div class = "select_address">
                    <table id="address_item" onclick="select_address()">
                                   <tr>
                                <td>
                                  收件人: <?php echo $address['name']?>
                                </td>
                                  <td>
                                  手机号码: <?php echo $address['phone']?>
                                </td>
                              </tr>
                              <tr>
                                  <td id="address_detail"><?php echo $address['province']; ?> <?php echo $address['city']; echo $address['address_detail']?></td>
                                  <td>邮编：<?php echo $address['zip']; ?></td>
                              </tr>
                        </table>  
                        <p><strong>订单号:<?php echo $order_id?></strong><em>时间:<?php echo $create_time?></em>
                        <strong>
                          <?php if($status == '0') {?>
                            待付款
                          <?php } else if($status == '1') {?>
                            已付款
                          <?php } else if($status == '2') {?>
                            已取消
                          <?php } else if($status == '3') {?>
                            已失效
                          <?php }?>  
                        </strong></p>
              </div>
                    <div>
                         <?php foreach ($carts as $item):?>
                         <table>
                                <tr>
                                    <tr id="header_part">
                                        <td id="demand_title">标题：<?php echo $item['title']; ?></td>
                                    </tr>
                                    <tr id="description_part">
                                        <td>描述：<?php echo $item['description']; ?></td>
                                    </tr>
                                     <tr id="footer_part">
                                        <td id="demand_price">工期：<?php echo $item['pro_time']; ?></td>
                                        <td>数量: <?php echo $item['number']?></td>
                                    </tr>
                                </tr>
                          </table>  
                        <?php endforeach;?>
                    </div>
                  <p><button id="pay_order">付款</button><button id="cancel_order">取消订单</button></p>
        </main>
    </div>
</body>
</html>