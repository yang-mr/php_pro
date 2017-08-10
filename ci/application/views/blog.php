<html>
<head>
    <title>My Blog</title>
    <?php echo $des;?>
</head>
<body>
    <h1>Welcome to my Blog!</h1>
    <h2><?php echo $title;?>
  <?php foreach ($list as $item):?>
    	<li><?php echo $item;?></li>
    <?php endforeach;?>
</body>
</html>