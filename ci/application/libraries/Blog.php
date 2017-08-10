<?php
	class Blog
	{
		public $title;
		private $des;

		public function __set($name, $value) {

		}

		public function __get($name) {
			if (isset($name)) {
				return $this->$name;
			}
		}

		public function testme() {
			return $this->title . "reverkdk";
		}
	}