<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/user_center.css" />
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
        <p id="user_name"> <?php echo get_data_from_cookie('username'); ?><a href="#" id='post_message'><?php if(get_data_from_cookie('type') == 0) {?>
        发布新需求
        <?php } else {?>
            发布新作品
        <?php }?>
        </a></p>
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
                         <table id="table_demand">
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
                        <?php echo $pages?>
                    </div>
                </div>
        </main>
        <aside></aside>
    </div>
    <footer>
    </footer>
</body>
</html>