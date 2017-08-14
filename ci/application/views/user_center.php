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
        function myload() {
            <?php if (isset($result)) {?>
            alert("<?php echo $result ?> ");
            <?php }?>
        }
   window.onload = myload;
 </script>
    </script>
</head>
<body>
    <?php echo validation_errors()?>
 <div id="dialog-form" title="发布新需求">
  <p class="validateTips">所有的表单字段都是必填的。</p>
  <?php echo form_open('user/post_message'); ?>
  <fieldset>
    <label for="name">标题</label>
    <input type="text" name="title" id="name" class="text ui-widget-content ui-corner-all">
    <label for="email">描述</label>
    <input type="text" name="description" id="email" value="" class="text ui-widget-content ui-corner-all">
    <label for="password">预算</label>
    <input type="text" name="price" id="password" value="" class="text ui-widget-content ui-corner-all">
     <label for="password">面积</label>
     <input type="hidden" name="id" value="<?php echo $id;?>"/>
    <input type="hidden" name="username" value="<?php echo $username;?>"/>
    <input type="text" name="area" id="password" value="" class="text ui-widget-content ui-corner-all">
    <input type="submit" value="发布新需求" class="text ui-widget-content ui-corner-all"/>
  </fieldset>
  </form>
</div>
    <header>
        <p id="user_name"><?php echo $username; ?><a href="#" id='post_message'>发布新需求</a></p>
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
           
                <?php foreach ($demands as $item):?>
                     <table id="table_demand">
                    <tr id="header_part">
                        <th id="demand_title">标题：<?php echo $item['title']; ?></th>
                        <th id="demand_area">面积: <?php echo $item['area']; ?></th>
                    </tr>
                    <tr id="description_part">
                        <th>装修描述：<?php echo $item['description']; ?></th>
                    </tr>
                      </table>  
                <?php endforeach;?>
        </main>
        <aside></aside>
    </div>
    <footer>

    </footer>
</body>

</html>