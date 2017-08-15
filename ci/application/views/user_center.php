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
  <?php $hidden = array('id' => $id, 'type' => $type, 'username' => $username);
       echo form_open('user/post_message', '', $hidden); ?>
  <fieldset>
    <label for="name">标题</label>
    <input type="text" name="title" id="name" class="text ui-widget-content ui-corner-all">
    <label for="email">描述</label>
    <input type="text" name="description" id="email" value="" class="text ui-widget-content ui-corner-all">
    <label for="password">预算</label>
    <input type="text" name="price" id="password" value="" class="text ui-widget-content ui-corner-all">
     <label for="password">面积</label>
   <!--   <input type="hidden" name="id" value="<?php echo $id;?>"/>
      <input type="hidden" name="type" value="<?php echo $type;?>"/>
    <input type="hidden" name="username" value="<?php echo $username;?>"/> -->
    <input type="text" name="area" id="password" value="" class="text ui-widget-content ui-corner-all">
    <input type="submit" value="发布新需求" class="text ui-widget-content ui-corner-all"/>
  </fieldset>
  </form>
</div>
<div id="dialog-form-worker-designer" title="添加项目简介">
  <p class="validateTips">所有的表单字段都是必填的。</p>
  <?php echo form_open_multipart('user/post_message'); ?>
  <fieldset>
    <label for="name">标题</label>
    <input type="text" name="title" id="name" class="text ui-widget-content ui-corner-all">
    <label for="email">描述</label>
    <input type="text" name="description" id="email" value="" class="text ui-widget-content ui-corner-all">
    <label for="password">项目时间</label>
    <input type="text" name="pro_time" value="" class="text ui-widget-content ui-corner-all">
     <label for="password">上传项目图片或者文件</label>
    <input type="file" name="userfile[]" value="" class="text ui-widget-content ui-corner-all">
    <input type="file" name="userfile[]" value="" class="text ui-widget-content ui-corner-all">
    <input type="file" name="userfile[]" value="" class="text ui-widget-content ui-corner-all">
    <input type="file" name="userfile[]" value="" class="text ui-widget-content ui-corner-all">
    <input type="file" name="userfile[]" value="" class="text ui-widget-content ui-corner-all">
    <input type="hidden" name="id" value="<?php echo $id;?>"/>
    <input type="hidden" name="type" value="<?php echo $type;?>"/>
    <input type="hidden" name="username" value="<?php echo $username;?>"/>
    <input type="submit" value="发布新需求" class="text ui-widget-content ui-corner-all"/>
  </fieldset>
  </form>
</div>
    <header>
        <p id="user_name"><?php echo $username; ?><a href="#" id='post_message'><?php if($type == '0') {?>
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
                <?php if($type == 0) {?>
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
                <?php endforeach;} else {?>

                     <?php foreach ($demands as $item):?>
                     <table id="table_demand">
                    <tr id="header_part">
                        <th id="demand_title">标题：<?php echo $item['title']; ?></th>
                    </tr>
                    <tr id="description_part">
                        <th>项目描述：<?php echo $item['description']; ?></th>
                    </tr>
                     <tr id="footer_part">
                        <th id="demand_price">项目周期：<?php echo $item['pro_time']; ?></th>
                        <th id="demand_time">发布时间: <?php echo $item['public_time']; ?></th>
                    </tr>
                      </table>  
                <?php endforeach;?>
                <?php }?>
        </main>
        <aside></aside>
    </div>
    <footer>
    </footer>
</body>
</html>