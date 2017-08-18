<?php
	class Test extends CI_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->model('blog_model', 'model');
			$this->load->library('pagination');
			$this->load->library('parser');
			$this->load->library('table');
			$this->load->library('unit_test');
			$this->load->library('zip');
			$this->load->helper('date');
		}

		public function test() {
			echo time() . PHP_EOL;
			echo now() . PHP_EOL;
			echo date('Y-m-d H:i:s');
		}

		public function curl() {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'www.baidu.com');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			//var_dump($output);
			$info = curl_getinfo($ch);
			var_dump($info);
			curl_close($ch);
		}
	}