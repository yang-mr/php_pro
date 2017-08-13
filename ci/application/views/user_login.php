<!DOCTYPE html>
<html>
<head>
	<title>用户登录</title>
</head>
<body>
	<?php echo validation_errors()?>
	<?php echo form_open('user/login')?>
	用户名<input type='text' name='username' value='<?php echo set_value('username')?>' size='20'/>
	<br/>
	用户密码<input type='password' name='password' value='<?php echo set_value('password')?>' size='20'/>
	<br/>
	<input type="submit" value="Submit" />
	</form>
</body>
</html>