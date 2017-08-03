<?php
	require_once "Base.class.php";
	/**
	* createwant table bean
	*/
	class WantBean extends Base
	{
		private $title;
		private $des;
		private $area;
		private $price;

		public function __construct($title, $des, $area, $price) 
		{
			$this->title = $title;
			$this->des = $des;
			$this->area = $area;
			$this->price = $price;
		}

		public function __set($property_name, $property_value) {
			echo "property_name:" . $property_name . " value: " . $property_value;
			$this->property_name = $property_value; 
		}

		public function __get($property_name) {
			echo "name: " . $this->$property_name . PHP_EOL;
			if (isset($this->$property_name)) {
				return $this->$property_name;
			}
		}
	}