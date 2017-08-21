<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/user_cart.css" />
    <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js">
                </script>
    <link href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css" rel="stylesheet">
                <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js">
                </script>
    <title>地址列表</title>
    <script>   
         function delete_cart_item(id) {
            $.post('<?php echo base_url() ?>good/delete_cart_item', {'cart_id':id}, function(data, status){
                        if ('success' == status) {
                            alert(data);
                            location.href="<?php echo base_url() ?>user/cart_list";
                        } else {
                            alert("删除购物车失败");
                        }
            });
        }

        function add_address() {
             var form = new FormData(document.getElementById('form_address'));
                      $.ajax({
                        url:"<?php echo base_url()?>user/add_address",
                        type:"post",
                        data:form,
                        processData:false,
                        contentType:false,
                        success:function(data){
                            alert(data);
                            location.href="<?php echo base_url()?>user/user_address";
                        },
                        error:function(e){
                            alert("错误！！");
                        }
                    });        
        }

        $(function() {
              //init
              $(".cart_delete").hide();

              $("#edit_address").click(function() {
                    $(".cart_delete").toggle();
              });

              $("#address_item").click(function() {
                  if (<?php isset($carts)?>) {
                      
                  }
              });

             $("#open_dialog").click(function() {
                  $("#dialog-form").dialog("open");
              });

               $("#dialog-form").dialog({
                    autoOpen: false,
                    height: 800,
                    width: 500,
                    modal: true,
                    buttons: {
                        Cancel: function() {
                            $(this).dialog("close");
                        }
                    },
                    close: function() {
                        $(this).dialog("close");
                    }
                });
        });
 </script>
</head>
<body>
<div id="dialog-form" title="添加新地址">
  <form id="form_address">
  <p class="validateTips">所有的表单字段都是必填的。</p>
    <label for="name">收件人</label>
    <input type="text" name="name" >
    <br/>
    <label for="email">手机号码</label>
    <input type="text" name="phone" value="" >
    <br/>
    <label for="password">省份</label>
    <input type="text" name="province" value="">
    <br/>
     <label for="password">城市</label>
    <input type="text" name="city" value="" >
    <br/>
     <label for="password">地址</label>
    <input type="text" name="address_detail" value="" >
    <br/>
      <label for="password">邮编</label>
    <input type="text" name="zip" value="000000" >
    <br/>
        <input type="button" value="发布新需求" onclick="add_address()">
    </form>
</div>

    <header>
             <div id="user_name">
              <a href="<?php echo base_url() ?>user/user_center">
              <?php echo get_data_from_cookie('username'); ?>
              </a>
              </div>    
              <a href="#" id='edit_address'>
            编辑地址 </a>
           
    </header>
    <div id="context">
        <nav>
            <ul>
                <li><a href="#" id="open_dialog">添加地址</a></li>
            </ul>
        </nav>
        <main>
                <div id="demands_content_main">
                    <div id="content_header"></div>
                    <div id="demands_content">
                         <?php foreach ($addresses as $item):?>
                         <table id="address_item" onclick="go_create(<?php echo $item['id']?>, <?php echo $carts?>)">
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