<html>

<head>
				<title>My Form</title>
</head>

<body>
				<?php echo validation_errors(); ?>
				<?php echo form_open('testform'); ?>
				<h5>Username</h5>
				<?php echo form_error('username') ?>
				<input type="text" name="username" value="<?php echo set_value('username');?>" size="50" />
				<h5>Password</h5>
				<input type="text" name="password" value="<?php echo set_value('password');?>" size="50" />
				<h5>Password Confirm</h5>
				<input type="text" name="passconf" value="" size="50" />
				<h5>Email Address</h5>
				<input type="text" name="email" value="" size="50" />
				<div>
								<input type="submit" value="Submit" />
				</div>
				<select name="myselect">
								<option value="one" <?php echo set_select( 'myselect', 'one', TRUE); ?> >One</option>
								<option value="two" <?php echo set_select( 'myselect', 'two'); ?> >Two</option>
								<option value="three" <?php echo set_select( 'myselect', 'three'); ?> >Three</option>
				</select>
				</form>
</body>

</html>