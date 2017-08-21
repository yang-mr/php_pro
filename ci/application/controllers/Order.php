<?php 
	class Order extends CI_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->model('order_model');
			$this->load->library('pagination'); //分页
		}

		public function create_order() {
			$this->order_model->create_order();
			//$this->load->view('user_address', $this->input->post('carts[]'));
		}
	}