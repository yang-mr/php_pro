<?php
	class Testform extends CI_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->load->library('image_lib');
		}

		public function index() {
			$config = array(
				'test'=>array( 
				array(
			        'field' => 'username',
			        'label' => 'Username',
			        'rules' => 'required'
			    ),
			    array(
			        'field' => 'password',
			        'label' => 'Password',
			        'rules' => 'required'
			    ),
			    array(
			        'field' => 'passconf',
			        'label' => 'Password Confirmation',
			        'rules' => 'required'
			    ),
			    array(
			        'field' => 'email',
			        'label' => 'Email',
			        'rules' => 'required'
			    ))
			);

			$this->form_validation->set_rules($config['test']);
			/*$this->form_validation->set_rules('username', '用户名', 'trim|required', array('required'=>'你必须填写数据'));

			$this->form_validation->set_rules('password', '密码', 'callback_pwd_check[kjkjk]');*/
			if ($this->form_validation->run() === false) {
				$this->load->view('myform');
			} else {
				$this->load->view('formsuccess');
			}
		}

		public function pwd_check($str) {
			if ($str == 'test') {
				/*$this->form_validation->set_message('pwd_check', 'The {field} field can not be the word "test"');*/
				$this->form_validation->set_message('message_check');	
				return false;
			} else {
				return true;
			}
		}

		public function message_check($str) {
			if ($str == 'test') {
				
				return "1111111111111";
			} else {
				return "123";
			}
		}

		public function testimg() {
			/*if (!$this->image_lib->watermark()) {
				echo $this->image_lib->display_errors();
			}*/
			var_dump($this->input->cookie('yw'));
		}

		public function testout() {
			$this->output->set_content_type('application/json', 'ut')
						 ->set_output(json_encode(array('title'=>'kfjd')));

						 echo $this->output->get_content_type();
						 echo $this->output->get_header('content-type');
			}
	}