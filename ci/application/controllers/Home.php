<?php
class Home extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('home_model');
		$this->load->library('pagination'); //分页
	}

	public function index($page = 0) {
		$demands = $this->home_model->index($page);
		$config['base_url'] = base_url() . 'home/index';
		$config['total_rows'] = $demands['sum'];
		$config['per_page'] = $this->config->item('per_page');
		$this->pagination->initialize($config);
		$demands['demands_pages'] = $this->pagination->create_links();
		var_dump($demands);
		$this->load->view('index', $demands);
	}
}