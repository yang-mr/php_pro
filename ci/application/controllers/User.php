<?php
class User extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('user_model');
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
			if ($result = $this->user_model->login_user()) {
				$this->load->view('user_center', $result);
			} else {
				 echo '登录失败';
			}
		}
	}

	public function post_message() {
		$array = array(
			array(
				'field'=>'title',
				'laber'=>'Title',
				'rules'=>'required|min_length[5]|max_length[40]'
			),
			array(
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
			)
		);

		$this->form_validation->set_rules($array);
		if (!$this->form_validation->run()) {
			$data = array(
				'username'=>$this->input->post('username'),
				'id'=>$this->input->post('id'),
				'result'=>''
			);
			$this->load->view('user_center', $data);
		} else  {
			$this->load->view('user_center', $this->user_model->insert_message());
		}
	}
}