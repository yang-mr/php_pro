<?php
	require_once "../vendor/autoload.php";
	require_once "../model/Db.class.php";
	require_once "../model/User.class.php";

	$action = $_POST['action'];
	switch ($action) {
		case 'login':
			$name = $_POST['username'];
			$password = $_POST['password'];
			echo login($name, $password);
			break;
		case 'register':
			$name = $_POST['username'];
			$password = $_POST['password'];
			$type = $_POST['type'];
			$user = new User($name, $password, $type);
			echo register($user);
			break;
		case 'quitaccount':  //退出账号
			echo quitaccount();
			break;
		default:
			echo getCookie("");
			break;
	}

	function login($name, $password) {
		$db = new Db();
		$result = $db->getLoginData($name, $password);
		if ($result == 2) {
			getCookie($name);
		}

		var_dump($app = new Application($options));
		//echo "login: " . $result . PHP_EOL;
		return $result;
	}

	function register(User $user) {
		$db = new Db();
		$result = $db->insertData($user);
		if ($result == 1) {
			getCookie($user->getName());
		}
		return $result;
	}

	function getCookie($name) {
	//	echo "getCookie: " . $name . PHP_EOL;
		//var_dump(isset($_COOKIE["name"]));
	//	var_dump($_COOKIE['name']);
		if (isset($_COOKIE["name"])) {
			return $_COOKIE['name'];
		} else {
			if ("" != $name) {
				setcookie("name", $name, time() + 3600);
			}
			return "0";
		}
	}

	function quitaccount() {
		setcookie("name", NULL, -1);
		return 1; 
	}