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
               $("#go_pay").click(function() {
                  <?php if(empty($address)) {?>
                    alert("请添加地址！！！");
                    return;
                  <?php }?>
                   $.get('<?php echo base_url() ?>order/create_order_ok/<?php echo $address['id']?>/<?php echo $cartids?>', function(data, status){
                        if ('success' == status) {
                            var tip = data.substring(0, 6);
                            if (tip == '生成订单成功') {
                                 var order_id = data.substring(6, data.length);
                                 location.href="<?php echo base_url() ?>order/order_detail/" + order_id;
                                 return;
                            }
                        }
                        alert("生成订单失败");
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
                              <?php if (empty($address)) {?>
                                <tr>请添加地址！！！</tr>
                              <?php } else {?>
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
                                <?php }?>
                             
                        </table>  
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
                <div id="operation_list">
                    <button id="go_pay">提交付款</button>
                </div>
        </main>
    </div>
</body>
</html>