<?php
class User extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('user_model');
	}

	public function index() {
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
		$this->form_validation->set_rules($array);
		if (!$this->form_validation->run()) {
			$this->load->view('user_login');
		} else  {
			if ($result = $this->user_model->login_user()) {
				$this->load->view('user_center', $result);
			} else {
				 echo '登录失败';
			}
		}
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
		$this->form_validation->set_rules($array);
		if (!$this->form_validation->run()) {
			$this->load->view('user_login');
		} else  {
			if ($this->user_model->login_user()) {
				header("Location:./user_center");
			} else {
				 echo '登录失败';
			}
		}
	}

	public function user_center() {
		$data = $this->user_model->get_user_center();
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
					$this->load->view('user_center', $this->user_model->insert_message());
				} else if ($type == "1") {
					//装修的人发布项目
					$this->load->view('user_center', $this->user_model->insert_worker());
				} else if ($type == "2") {
					//设计师发布项目
					$this->load->view('user_center', $this->user_model->insert_designer());
				}
		}
		header("Location:./user_center");
	}

	public function delete_message($id = 0) {
		$type = get_data_from_cookie('type');
		if ($id != 0) {
			if ($type == 0) {
				$this->user_model->delete_message($id);
			} else if ($type == 1) {
				$this->user_model->delete_worker($id);
			} else if ($type == 2) {
				$this->user_model->delete_designer($id);
			}
		} else {

		}
		header('Location:./../user_center');
	}

	public function update_message($id = 0, $typeid = 0) {
		$type = get_data_from_cookie('type');
		if ($typeid != 0) {
			$tmpid = $this->uri->segment(3);
			if ($type == 0) {
				if ($this->user_model->update_message($tmpid)) {
						header('Location:./../../user_center');
				} else {
						echo "更新失败";
				}
			} else if ($type == 1) {
				$this->user_model->delete_worker($tmpid);
			} else if ($type == 2) {
				$this->user_model->delete_designer($tmpid);
			}
		
		} else {
			//显示更新界面
			if ($type == 0) {
				$data = $this->user_model->get_message_row($id);
				$this->load->view('update_message', $data);
			} else if ($type == 1) {
				$this->user_model->delete_worker($id);
			} else if ($type == 2) {
				$this->user_model->delete_designer($id);
			}
		}
	}
}