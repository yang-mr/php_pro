<?php
class Good extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('good_model');
	}

	public function item($id = 0, $type = 0) {
		if ($id != 0) {
			$this->load->view('good_item', $this->good_model->get_good($id, $type));
		}
	}

	public function add_cart($id = 0, $type = 1) {
		if ($id != 0) {
			echo $this->good_model->add_cart($id, $type);
		}
	}

	public function change_cart_count() {
			echo $this->good_model->change_cart_count();
	}

	public function delete_cart_item() {
			echo $this->good_model->delete_cart_item();
	}
}