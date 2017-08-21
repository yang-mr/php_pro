<?php
class User extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->library('pagination'); //分页
	}

	/*
	用户注册
	*/
	public function register() {
		$array = array(
			array(
				'field'=>'username',
				'laber'=>'Username',
				'rules'=>'required|min_length[5]|max_length[10]'
			),
			array(
				'field'=>'password',
				'laber'=>'Password',
				'rules'=>'required|min_length[6]'
			),
			array(
				'field'=>'password2',
				'laber'=>'Password2',
				'rules'=>'required|min_length[6]'
			),
			array(
				'field'=>'password2',
				'laber'=>'Password2',
				'rules'=>'required|min_length[6]'
			),
			array(
				'field'=>'password2',
				'laber'=>'Password2',
				'rules'=>'required|min_length[6]'
			),
		);
		$this->form_validation->set_rules($array);
		if (!$this->form_validation->run()) {
			$this->load->view('user_register');
		} else  {
			if ($this->user_model->insert_user()) {
			     	$this->load->view('user_login');
			} else {
				 echo '注册失败';
			}
		}
	}

	public function login() {
		$array = array(
			array(
				'field'=>'username',
				'laber'=>'Username',
				'rules'=>'required|min_length[5]|max_length[10]'
			),
			array(
				'field'=>'password',
				'laber'=>'Password',
				'rules'=>'required|min_length[6]'
			)
		);

	/*	$this->form_validation->set_rules($array);
		if (!$this->form_validation->run()) {
			$this->load->view('user_login');
		} else  {
			var_dump($array);
			if ($this->user_model->login_user()) {
					echo '登录成功';
					exit;
				header("Location:./user_center");
			} else {
				 echo '登录失败';
			}
		}*/
			$first = $this->input->post('username');
			if (!isset($first)) {
				$this->load->view('user_login');
				return;
			}
		    
			if ($this->user_model->login_user()) {
				 echo '登录成功';
			} else {
				 echo '登录失败';
			}
	}

	public function logout() {
		setcookie('username', '', time() - 10, '/');
		setcookie('id', '', time() - 10, '/');
		setcookie('type', '', time() - 10, '/');
		header("Location:" . base_url() . "user/login");	
		exit;    
	}

	//购物车列表
	public function cart_list($page = 0) {
		$data = $this->user_model->get_cart_list($page);
		
		$config['base_url'] = base_url() . 'user/cart_list';
		$config['total_rows'] = $this->user_model->get_count_fitmentcart_table();
		$config['per_page'] = $this->config->item('per_page');
		$this->pagination->initialize($config);
		$data['pages'] = $this->pagination->create_links();
		$this->load->view('user_cart', $data);
	}

	//地址列表
	public function user_address($page = 0) {
		$data = $this->user_model->get_address_list($page);
		
		$config['base_url'] = base_url() . 'user/user_address';
		$config['total_rows'] = $this->user_model->get_count_fitment_address_table();
		$config['per_page'] = $this->config->item('per_page');
		$this->pagination->initialize($config);
		$data['pages'] = $this->pagination->create_links();

		$this->load->view('user_address', $data);
	}

		//地址列表
	public function add_address() {
		echo $this->user_model->add_address();
	}

	public function user_center($page = 0) {
		$data = $this->user_model->get_user_center($page);

		$config['base_url'] = base_url() . 'user/user_center';
		$config['total_rows'] = $this->user_model->get_count();
		$config['per_page'] = $this->config->item('per_page');
		$this->pagination->initialize($config);

		$data['pages'] = $this->pagination->create_links();
		$this->load->view('user_center', $data);
	}

	public function post_message() {
		$array = array(
			array(
				'field'=>'title',
				'laber'=>'Title',
				'rules'=>'required|min_length[5]|max_length[40]'
			),
			/*array(
				'field'=>'description',
				'laber'=>'Desc',
				'rules'=>'required|min_length[6]'
			),
			array(
				'field'=>'price',
				'laber'=>'price',
				'rules'=>'required'
			),
			array(
				'field'=>'area',
				'laber'=>'area',
				'rules'=>'required'
			)*/
		);

		$this->form_validation->set_rules($array);
		$type = get_data_from_cookie('type');
		if (!$this->form_validation->run()) {
			$this->load->view('user_center');
		} else  {
				if ($type == "0") {
					//需要装修的人
					echo $this->user_model->insert_message();
				} else if ($type == "1") {
					//装修的人发布项目
					echo $this->user_model->insert_worker();
				} else if ($type == "2") {
					//设计师发布项目
					$this->load->view('user_center', $this->user_model->insert_designer());
				}
		}
	}

	public function delete_message($id = 0) {
		$type = get_data_from_cookie('type');
		if ($id != 0) {
			if ($type == 0) {
				echo $this->user_model->delete_message($id);
			} else if ($type == 1) {
				echo $this->user_model->delete_worker($id);
			} else if ($type == 2) {
				echo $this->user_model->delete_designer($id);
			}
		} else {
			echo "删除失败";
		}
	}

	public function update_message($id = 0, $typeid = 0) {
		$type = get_data_from_cookie('type');
		if ($typeid != 0) {
			$tmpid = $this->uri->segment(3);
			if ($type == 0) {
				echo $this->user_model->update_message($tmpid);
			} else if ($type == 1) {
				if ($this->user_model->update_worker($tmpid)) {
						header('Location:' . base_url() . 'user/user_center');
				} else {
						echo "更新失败";
				}
			} else if ($type == 2) {
				$this->user_model->delete_designer($tmpid);
			}
		} else {
			//显示更新界面
			$data = $this->user_model->get_message_row($id);
			$this->load->view('update_message', $data);
		}
	}
}