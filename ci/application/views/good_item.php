<html>
<head>
      <title>商品详情</title>
    <meta charset="UTF-8">
                <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>
         function go_cart() {
                  var type = <?php echo $type ?>;
                  var good_id = <?php echo $id ?>;
                  $.get("../../../good/add_cart/" + good_id + "/" + type, function(data, status){
                      if (status == 'success') {
                          alert(data);
                          location.href="<?php echo base_url();?>user/cart_list";
                      } else {
                        alert('加入购物车失败');
                      }
                  });
          }
 </script>
</head>
<body>
    <label for="name">标题：</label><?php echo $title?>
     <br/>
    <label for="email">描述</label><?php echo $description?>
    <br/>
    <label for="password">项目周期</label><?php echo $pro_time?>
    <br/>
    <div><button onclick="go_cart()">加入购物车</button onclick="go_buy()"><button>立即购买</button></div>
    <?php foreach ($images as $image):?>
        <img src="<?php echo $this->config->item('qiniu_domain');?><?php echo $image['res_url'];?>">
    <?php endforeach;?>
  </div>
</body>
</html>