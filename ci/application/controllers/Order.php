<?php 
	class Order extends CI_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->model('order_model');
			$this->load->library('pagination'); //分页
			$this->load->helper('url');
		}

		public function create_order($address_id = 0, $carts) {
			$data = $this->order_model->create_order($address_id, $carts);
			$this->load->view('create_order', $data);
		}

		public function create_order_ok($address_id = 0, $carts) {
			echo $this->order_model->create_order_ok($address_id, $carts);
		}
	}