<?php
	//require_once "../model/Db.class.php";
	require_once "../controller/login.php";

	function __autoload($name) {
		require_once "../model/" . $name . ".class.php";
	}

	$db = new Db();
	//insert data
	// $user = new User("rose", "123");
 //    echo $db->insertData($user);

	//delete data
	//$db->delData("jack");

	//test login
	//echo $db->getLoginData('rose', '123');

	//test cookie
	login("rose", "123");


