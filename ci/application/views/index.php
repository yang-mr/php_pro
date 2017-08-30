<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/index.css" />
<script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js">
                </script>
<link href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css" rel="stylesheet">
 <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js">
                </script>
<script language="JavaScript" src="<?php echo base_url() ?>public/js/user_center.js"></script>
    <title>个人中心</title>
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

        function item_detail(id, type) {
            if (<?php echo is_login()?>) {
                location.href = '<?php echo base_url()?>good/item/' + id + "/" + type;
            } else {
                alter('请登陆后操作');
                location.href = '<?php echo base_url()?>user/login';
            }
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
    <div id="wrapper">
           <header>
                <ul>
                <?php if (is_login()) {?>
                      <li><a href="<?php echo base_url() ?>user/user_center">
                      <?php echo get_data_from_cookie('username'); ?></a></li>
                      <li><a href="#" id='post_message'>
                      <?php if(get_data_from_cookie('type') == 0) {?> 发布新需求<?php } else {?>
                      发布新作品<?php }?> </a></li>
               <?php } else {?>
                        <li><a href="<?php echo base_url()?>user/login">登陆</a></li>
                        <li><a href="<?php echo base_url()?>user/register">注册</a></li>
               <?php }?>
               </ul>
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
                         <?php foreach ($demands as $item):?>
                         <table id="table_demand" onclick="item_detail(<?php echo $item['id']?>, <?php echo $demands_type?>)">
                        <tr id="header_part">
                            <th id="demand_title">标题：<?php echo $item['title']; ?></th>
                            <th id="demand_area">面积: <?php echo $item['area']; ?></th>
                        </tr>
                        <tr id="description_part">
                            <th>装修描述：<?php echo $item['description']; ?></th>
                        </tr>
                         <tr id="footer_part">
                            <th id="demand_price">预算：<?php echo $item['price']; ?></th>
                            <th id="demand_time">发布时间: <?php echo $item['public_date']; ?></th>
                        </tr>
                          </table>  
                        <?php endforeach;?>
                    </div>
                    <div id="content_footer">
                        <?php echo $demands_pages?>
                    </div>
                </div>

                <div id="demands_content_main">
                    <div id="content_header"></div>
                    <div id="demands_content">
                         <?php foreach ($workers as $item):?>
                         <table id="table_demand" onclick="item_detail(<?php echo $item['id'] ?>, <?php echo $workers_type?>)">
                        <tr id="header_part">
                            <th id="demand_title">标题：<?php echo $item['title']; ?></th>
                        </tr>
                        <tr id="description_part">
                            <th>装修描述：<?php echo $item['description']; ?></th>
                        </tr>
                         <tr id="footer_part">
                            <th id="demand_price">工期：<?php echo $item['pro_time']; ?></th>
                            <th id="demand_time">发布时间: <?php echo $item['public_time']; ?></th>
                        </tr>
                          </table>  
                        <?php endforeach;?>
                    </div>
                    <div id="content_footer">
                        <?php echo $workers_pages?>
                    </div>
                </div>
        </main>
           <aside>我在右边</aside>
    </div>
    <footer>
        <p>@2017版权所有</p>
        <nav>
            <ul><a href="#">关于我们</a></ul>
            <ul><a href="#">联系我们</a></ul>
        </nav>
    </footer>
    </div>
</body>
</html>