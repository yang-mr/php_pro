<!DOCTYPE html>
<html>
<head>
	<title>用户注册</title>
</head>
<body>
	<?php echo validation_errors()?>
	<?php echo form_open('user/register')?>
	用户名<input type='text' name='username' value='<?php echo set_value('username')?>' size='20'/>
	<br/>
	用户密码<input type='password' name='password' value='<?php echo set_value('password')?>' size='20'/>
	<br/>
	重复密码<input type='password' name='password2' value='<?php echo set_value('password2')?>' size='20'/>
			<br/>
	手机号码<input type='text' name='phone' value='<?php echo set_value('phone')?>' size='20'/>
	<h5>选择性别</h5>
	男<input type='radio' name='fitment_sex' value='true' checked/>
	女<input type='radio' name='fitment_sex' value='false'/>
	<h5>选择注册类型</h5>
	需要装修<input type='radio' name='fitment_type' value='0' size='20'/ checked>
	装修师傅<input type='radio' name='fitment_type' value='1' size='20'/>
	设计师<input type='radio' name='fitment_type' value='2' size='20'/ >
	<br/>
	<input type="submit" value="Submit" />
	</form>
</body>
</html>