<?php
	require_once "Base.class.php";
	
	class User extends Base {
		private $name;
		private $password;

		public function __construct($name, $password) {
			$this->name = $name;
			$this->password = $password;
		}

		public function getName() {
			return $this->name;
		}

		public function getPassword() {
			return $this->password;
		}
	}