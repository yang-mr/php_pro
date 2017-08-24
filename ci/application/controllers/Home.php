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

	public function index($page = 0, $type=0) {
		$demands = $this->home_model->index($page, $type);
		$config['base_url'] = base_url() . 'home/index';
		$config['total_rows'] = $this->home_model->get_count(0);
		$config['per_page'] = $this->config->item('per_page');
		$config['first_link'] = '首页';
		$config['last_link'] = '最后一页';
		$config['suffix'] = '/0';
		if ($type == 0) {
			$config['uri_segment'] = 3;
		}
		$this->pagination->initialize($config);
		$demands['demands_pages'] = $this->pagination->create_links();
		$demands['demands_type'] = "0";
		//$this->load->view('index', $demands);

//worker
		//$demands = $this->home_model->index($page, $type);
	//	$config['base_url'] = base_url() . 'home/index';
		$config['total_rows'] = $this->home_model->get_count(1);
		// $config['per_page'] = $this->config->item('per_page');
		// $config['first_link'] = '首页';
		// $config['last_link'] = '最后一页';
		$config['suffix'] = '/1';
		if ($type == 1) {
			$config['uri_segment'] = 3;
		}
		$this->pagination->initialize($config);
		$demands['workers_pages'] = $this->pagination->create_links();
		$demands['workers_type'] = "1";
		//开启缓存
		$this->output->cache(1);
		$this->load->view('index', $demands);
		
	}
}