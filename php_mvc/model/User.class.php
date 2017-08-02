<?php
	require_once "Base.class.php";
	
	class User extends Base {
		private $name;
		private $password;
		private $type;

		public function __construct($name, $password, $type) {
			$this->name = $name;
			$this->password = $password;
			$this->type = $type;
		}

		public function getName() {
			   return $this->name;
		}  

		public function getPassword() {
			return $this->password;
		}

		public function getType() {
			return $this->type;
		}
	}