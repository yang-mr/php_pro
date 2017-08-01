<?php

class Db
{
	public $mysql_conf = array(
		'local'=>'localhost',
		'username'=>'root',
		'password'=>'',
		'db'=>'php_mvc'
	);

	private function getConn() {
		$conn = mysqli_connect($this->mysql_conf['local'], $this->mysql_conf['username'], $this->mysql_conf['password'], $this->mysql_conf['db']);
		if (!$conn) {
			die($conn->error);
		} else {
			mysqli_query($conn, "set names 'utf8';");
			return $conn;
		}
	}

	public function insertData(User $user) {
		$returnResult = 2;   //0该用户已存在 1添加成功  2添加失败
		$conn = self::getConn();
		$name = $user->getName();
		$password = $user->getPassword();
		$result = $conn->query("select * from user where username = '$name'");
		// var_dump($result->fetch_row());
		// echo $name;
		// exit;
		if ($result->fetch_row()) {
			$returnResult = 0;
		} else {
			$stmt = $conn->prepare("insert into user(username, password) values (?, ?);");
			if (!$stmt) {
				die($conn->error);
			}

			$tmpPwd = md5($password);
			$stmt->bind_param('ss', $name, $tmpPwd);
			
			$stmt->execute();
			mysqli_stmt_close($stmt);
			$returnResult = 1;
		}
		return $returnResult;
		mysqli_close($conn);
	}

	public function delData($name) {
		$conn = self::getConn();
		$stmt = $conn->prepare("delete from user where username = ?");
		if (!$stmt) {
			die($conn->error);
		}
		$stmt->bind_param('s', $name);

		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	public function getLoginData($username, $password) {
		$conn = self::getConn();
		$returnResult = 2; //0用户名错误 1密码错误 2登录成功
		$result = $conn->query("select password from user where username = '$username'");
		$arr = $result->fetch_row();
		if (!$arr) {
			$returnResult = 0;
		} else {
			if (md5($password) == $arr[0]) {
				$returnResult = 2;
			} else {
				$returnResult = 1;
			}
		}
		$conn->close();
		return $returnResult;
	}

	public function getRow($sql) {
		$conn = self::getConn();
		$result = mysqli_query($conn, $sql);
		if ($result) {
			while ($row = mysqli_fetch_row($result)) {
				var_dump($row);
			}
		}
		$conn.close();
	}

	public function getRows($sql) {
		$conn = getConn();
		$result = mysqli_query($conn, $sql);
		while ($row = mysqli_fetch_array($result)) {
			var_dump($row);
		}
	}
}
	