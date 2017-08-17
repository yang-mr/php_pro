<head>
    <meta charset="UTF-8">
                <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js">
                </script>
    <link href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css" rel="stylesheet">
                <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js">
    <title>个人中心</title>
    <script>
        function myload() {
            <?php if(isset($_COOKIE['result']) && $_COOKIE['result'] != ""){ ?>
            alert("<?php echo $_COOKIE['result']; setcookie('result', ''); ?>");
            <?php } ?>
        }
   window.onload = myload;
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
           <?php echo validation_errors(); if ($_COOKIE['type'] == 0) {?>

 <div id="dialog-form" title="发布新需求">
  <p class="validateTips">所有的表单字段都是必填的。</p>
  <?php echo form_open('user/update_message/' . $id . '/10'); ?>
  <fieldset>
    <label for="name">标题</label>
    <input type="text" name="title" id="name" class="text ui-widget-content ui-corner-all" value="<?php echo $title?>">
    <label for="email">描述</label>
    <input type="text" name="description" id="email" class="text ui-widget-content ui-corner-all" value="<?php echo $description?>">
    <label for="password">预算</label>
    <input type="text" name="price" id="password" class="text ui-widget-content ui-corner-all" value="<?php echo $price?>">
     <label for="password">面积</label>
    <input type="text" name="area" id="password" class="text ui-widget-content ui-corner-all" value="<?php echo $area?>">
    <input type="submit" value="更改新需求" class="text ui-widget-content ui-corner-all">
  </fieldset>
  </form>
  </div>
<?php } else {?>
    <div id="dialog-form" title="更新新需求">
  <p class="validateTips">所有的表单字段都是必填的。</p>
  <?php echo form_open_multipart('user/update_message/' . $id . '/10'); ?>
  <fieldset>
    <label for="name">标题</label>
    <input type="text" name="title" id="name" class="text ui-widget-content ui-corner-all" value="<?php echo $title?>">
    <label for="email">描述</label>
    <input type="text" name="description" id="email" class="text ui-widget-content ui-corner-all" value="<?php echo $description?>">
    <label for="password">项目周期</label>
    <input type="text" name="pro_time" class="text ui-widget-content ui-corner-all" value="<?php echo $pro_time?>">
    <input type="submit" value="更改新需求" class="text ui-widget-content ui-corner-all">
    <?php foreach ($images as $image):?>
        <img src="<?php echo $this->config->item('qiniu_domain');?><?php echo $image['res_url'];?>">
        <input type="file" name="userfile[]" value="<?php echo $image['image_id']?>" class="text ui-widget-content ui-corner-all">
        <input type='hidden' name='ids[]' value="<?php echo $image['image_id'];?>,<?php echo $image['res_url'];?>">
    <?php endforeach;?>
  </fieldset>
  </form>
  </div>
<?php }?>
</body>
</html>