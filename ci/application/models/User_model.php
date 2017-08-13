<?php
	class User_model extends CI_Model {
		public  function __construct() {
			parent::__construct();
        			$this->load->database();
		}

		public function insert_user() {
			$pwd = $this->input->post('password');
			$tmp = $pwd . $this->config->item('pwd_salt');

			$array = array(
				'username'=>$this->input->post('username'),
				'password'=>md5($tmp),
				'sex'=>$this->input->post('fitment_sex'),
				'phone'=>$this->input->post('phone'),
				'type'=>$this->input->post('fitment_type'),
				'ip_address'=>$_SERVER["REMOTE_ADDR"],
				'register_date'=>date('Y-m-d H:i:sa')
			);

			return $this->db->insert('fitment_user', $array);
		}

		public function login_user() {
			$pwd = $this->input->post('password');
			$username = $this->input->post('username');
			$tmp = $pwd . $this->config->item('pwd_salt');
			$sql = "select password, id from fitment_user where username='" . $username . "'";
			$data = $this->db->query($sql)->row_array();
			if (empty($data)) {
				return false;
			} else if (md5($tmp) === $data['password']){
				return array('id'=>$data['id'], 'username'=>$username);
			} else {
				return false;
			}
		}

		/*
		需要装修的人的 发布的需求
		*/
		public function insert_message() {
			$id = $this->input->post('id');
			$array = array(
				'user_id'=>$id,
				'title'=>$this->input->post('title'),
				'description'=>$this->input->post('description'),
				'price'=>$this->input->post('price'),
				'public_date'=>date('Y-m-d H:i:sa'),
				'area'=>$this->input->post('area')
			);
			$result = array(
				'id'=>$id,
				'username'=>$this->input->post('username')
			);
			if ($this->db->insert('fitment_demands', $array)) {
				$result['result'] = '发布成功';
			} else {
				$result['result'] = '发布失败';
			}
			return $result;
		}
	}