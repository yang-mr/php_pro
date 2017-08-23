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

		public function order_detail($order_id = 0) {
			$this->load->view('order_detail', $this->order_model->order_detail($order_id));
		}

		public function cancel_order($order_id = 0) {
			echo $this->order_model->cancel_order($order_id);
		}

		public function user_order($status = 0, $page = 0) {
			$data = $this->order_model->user_order($status, $page);
			
			$config['base_url'] = base_url() . 'order/user_order';
			$config['total_rows'] = $this->order_model->get_rows_from_order($status);
			$config['per_page'] = $this->config->item('per_page');
			$this->pagination->initialize($config);
			$data['pages'] = $this->pagination->create_links();

			$this->load->view('user_order', $data);
		}
	}