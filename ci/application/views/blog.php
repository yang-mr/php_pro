<html>
<head>
    <title>My Blog</title>
<<<<<<< b11cfbee8a6c9b883f28d7d56d4616fb18483382
    {title}
    {des}
</head>
<body>
    <h1>Welcome to my Blog!</h1>
   <!--  <h2><?php echo $title;?>
  <?php foreach ($list as $item):?>
    	<li><?php echo $item;?></li>
    <?php endforeach;?> -->

    {lists}
    	{title}
    	{des}
    {/lists}
=======
</head>
<body>
    <h1>Welcome to my Blog!</h1>
    <?php echo $this->benchmark->elapsed_time() ?>
    {elapsed_time}
>>>>>>> email send test;
</body>
</html>