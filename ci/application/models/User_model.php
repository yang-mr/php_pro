<?php
	//require_once './vendor/autoload.php';

	use Qiniu\Auth;
    use Qiniu\Storage\UploadManager;
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
			$sql = "select type, password, id from fitment_user where username='" . $username . "'";
			$data = $this->db->query($sql)->row_array();
			if (empty($data)) {
				return false;
			} else if (md5($tmp) === $data['password']){
				$id = $data['id'];
				$type = $data['type'];
				/*$data = array('id'=>$id, 'username'=>$username, 'type'=>$type);*/
				/*
					用cookie 保存
				 */
				setcookie('id', $id . '', time() + 3600);
				setcookie('username', $username, time() + 3600);
				setcookie('type', $type . '', time() + 3600);
				setcookie('result', '登录成功', time() + 3600);
				return true;
			} else {
				return false;
			}
		}

		public function get_user_center($startindex = 0) {
			$type = get_data_from_cookie('type');
			$id = get_data_from_cookie('id');
			if ($type == 0) {
					//去获取需要装修的人发布的信息
					$sql = "select demand_id, title, description, price, area, 
					public_date from fitment_demands  where user_id = ? limit ?,?";
					$array = array(
						$id, $startindex + "", $this->config->item('per_page')
					);
					$data['demands'] = $this->db->query($sql, $array)->result_array();
				} else if ($type == 1) {
					//去获取装修师傅发布的信息
					$sql = "select title, description, pro_time, public_time from fitment_worker where user_id = " . $id;
					$data['demands'] = $this->db->query($sql)->result_array();
				} else if ($type == 2) {
					//去获取设计师发布的信息
					$sql = "select title, description, pro_time, public_time from fitment_designer where user_id = " . $id;
					$data['demands'] = $this->db->query($sql)->result_array();
				}
			return $data;
		}

		 /*
			得到一行数据
		*/
		public function get_message_row($id = 0) {
			$type = get_data_from_cookie('type');
			$table = 'fitment_demands';
			$sql = '';
			if ($type == 0) {
				$sql = 'select demand_id, title, description, area, price from fitment_demands where demand_id = ' . $id;
			} else if ($type == 1) {
				$sql = 'select worker_id, title, description, pro_time from fitment_worker where worker_id = ' . $id;
			} else if ($type == 2) {
				$sql = 'select designer_id, title, description, pro_time from fitment_designer where designer_id = ' . $id;
			}
			return $this->db->query($sql)->row_array();
		}

		 /*
			得到一行数据
		*/
		public function get_count() {
			$type = get_data_from_cookie('type');
			$id = get_data_from_cookie('id');
			$sql = '';
			if ($type == 0) {
				$sql = 'select count(demand_id) a from fitment_demands where user_id = ' . $id;
			} else if ($type == 1) {
				$sql = 'select worker_id, title, description, pro_time from fitment_worker where worker_id = ' . $id;
			} else if ($type == 2) {
				$sql = 'select designer_id, title, description, pro_time from fitment_designer where designer_id = ' . $id;
			}
			return $this->db->query($sql)->row_array()['a'];
		}

		/*
		需要装修的人的 发布的需求
		*/
		public function insert_message() {
			$id = get_data_from_cookie('id');
			$array = array(
				'title'=>$this->input->post('title'),
				'description'=>$this->input->post('description'),
				'price'=>$this->input->post('price'),
				'public_date'=>date('Y-m-d H:i:s'),
				'area'=>$this->input->post('area'),
				'user_id'=>$id
			);
			if ($this->db->insert('fitment_demands', $array)) {
				setcookie('result', '发布成功', time() + 3600);
			} else {
				setcookie('result', '发布失败', time() + 3600);
			}
		}
        
        /*
		需要装修的人的 删除需求
		*/
		public function delete_message($demand_id = 0) {
			$delresult = $this->db->query('delete from fitment_demands where demand_id=' . $demand_id);
			if ($delresult) {
				setcookie('result', '删除成功', time() + 3600);
			} else {
				setcookie('result', '删除失败', time() + 3600);
			}
		}

		/*
		需要装修的人的 更改需求
		*/
		public function update_message($id) {
			$title = $this->input->post('title');
			$description = $this->input->post('description');
			$price = $this->input->post('price');
			$area = $this->input->post('area');

		/*	$sql = "update fitment_demands set title = ?, description = ?, price = ?, area = ?, public_date = ? where demand_id = ?";*/
			$array = array('title'=>$title, 'description'=>$description, 'price'=>$price, 'area'=>$area, 'public_date'=>date("Y-m-d H:i:s"));
			$sql = $this->db->update_string('fitment_demands', $array, 'demand_id=' . $id);

			$updateresult = $this->db->query($sql);
			if ($updateresult) {
				setcookie('result', '更新成功', time() + 3600);
				return true;
			} else {
				return false;
			}
		}

			/*
		装修师傅 发布的项目
		*/
		public function insert_worker() {
		  $id = $this->input->post('id');
			$result = array(
				'id'=>$id,
				'username'=>$this->input->post('username'),
				'type'=>$this->input->post('type')
			);

			$array = array(
				'user_id'=>$id,
				'title'=>$this->input->post('title'),
				'description'=>$this->input->post('description'),
				'public_time'=>date('Y-m-d H:i:s'),
				'pro_time'=>$this->input->post('pro_time')
			);
			if ($this->db->insert('fitment_worker', $array)) {
				$worker_id = $this->db->query('select max(worker_id) worker_id from fitment_worker')->result()[0]->worker_id;

				  $file = $_FILES['userfile'];
				  $accessKey = $this->config->item('qiniu_ak');
				  $secretKey = $this->config->item('qiniu_sk');
				  $auth = new Auth($accessKey, $secretKey);
				  $bucket = 'test2';
				  // 生成上传Token
				  $token = $auth->uploadToken($bucket);
				  // 构建 UploadManager 对象
				  $uploadMgr = new UploadManager();
				  for ($i=0; $i < count($file); $i++) {
				  		$name = $file['name'][$i];
				  		if ("" != $name) {
				  			$uploadresult = $uploadMgr->putFile($token, 'fitment_' . mt_rand() . time() , $file['tmp_name'][$i]);
				  			$type = substr($file['type'][$i], 0, 5) == 'image' ? 0 : 1;
					  		$upload = array(
					  			'worker_id'=>$worker_id,
					  			'type'=>$type,
					  			'res_url'=>$uploadresult[0]['key'],
					  			'res_time'=>date('Y-m-d H:i:s')
					  			);
					  		if (!$this->db->insert('fitment_res', $upload)) {
					  			$result['result'] = '有些图片上传失败';
					  		}
				  		 } 
				  }
				$result['result'] = '发布成功';
			} else {
				$result['result'] = '发布失败';
			}
			//去获取装修师傅发布的信息
			$sql = "select title, description, pro_time, public_time from fitment_worker where user_id = " . $id;
			$result['demands'] = $this->db->query($sql)->result_array();
			return $result;
		}

		/*
		设计师 发布的项目
		*/
		public function insert_designer() {
			$id = $this->input->post('id');
			$array = array(
				'user_id'=>$id,
				'title'=>$this->input->post('title'),
				'description'=>$this->input->post('description'),
				'public_time'=>date('Y-m-d H:i:s'),
				'pro_time'=>$this->input->post('pro_time')
			);
			$result = array(
				'id'=>$id,
				'username'=>$this->input->post('username'),
				'type'=>$this->input->post('type')
			);
			if ($this->db->insert('fitment_designer', $array)) {
				$result['result'] = '发布成功';
			} else {
				$result['result'] = '发布失败';
			}
			//去获取设计师发布的信息
			$sql = "select title, description, pro_time, public_time from fitment_designer where user_id = " . $id;
			$result['demands'] = $this->db->query($sql)->result_array();
			return $result;
		}
	}