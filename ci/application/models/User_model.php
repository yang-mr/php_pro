<?php
	require_once './vendor/autoload.php';
	require_once 'Base_model.php';

	use Qiniu\Auth;
    	use Qiniu\Storage\UploadManager;
    	use Qiniu\Storage\BucketManager;
	class User_model extends Base_model {
		public  function __construct() {
			parent::__construct();
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
			$sql = "select type, password, id from fitment_user where username='" . $username . "'";
			$data = $this->db->query($sql)->row_array();
			if (empty($data)) {
				return false;
			} else if ($pwd === $data['password']){
				$id = $data['id'];
				$type = $data['type'];
				/*$data = array('id'=>$id, 'username'=>$username, 'type'=>$type);*/
				/*
					用cookie 保存
				 */
				setcookie('id', $id . '', time() + 60 * 60 * 24 * 7, "/");
				setcookie('username', $username, time() + 60 * 60 * 24 * 7, "/");
				/*session_start();
				$_SESSION['username'] = $username;*/
				setcookie('type', $type . '', time() + 60 * 60 * 24 * 7, "/");
				return true;
			} else {
				return false;
			}
		}

		public function get_address_list($page) {
			$per_page = $this->config->item('per_page');
			$id = get_data_from_cookie('id');

			$query = $this->db->query('select address_id id, name, phone, province, city, address_detail, zip from fitment_address where user_id = ' . $id . ' limit ' . $page . ',' . $per_page);
			$result['addresses'] = $query->result_array();
			return $result;
		}

		public function add_address() {
			$id = get_data_from_cookie('id');
			$data = array(
				'user_id'=>$id,
				'name'=>$this->input->post('name'),
				'phone'=>$this->input->post('phone'),
				'province'=>$this->input->post('province'),
				'city'=>$this->input->post('city'),
				'address_detail'=>$this->input->post('address_detail'),
				'zip'=>$this->input->post('zip'),
				'add_time'=>date('Y:m:d H:m:sa')
				);
			$result = $this->db->insert('fitment_address', $data);
			if ($result) {
				return "添加地址成功";
			}
			return "添加地址失败";
		}

		public function get_cart_list($page = 0) {
			$per_page = $this->config->item('per_page');
			$i = 0;
			$tmp = array();
			$id = get_data_from_cookie('id');
			$query = $this->db->query('select cart_id, worker_id, designer_id, public_time, 
				number from fitment_cart where user_id = ' . $id . ' and type = 1 limit ' . $page . ', ' . $per_page);
			$cartdata = $query->result_array();
			foreach ($cartdata as $item) {
				$data = array();
				if ($item['worker_id'] != null) {
					$data = $this->db->query('select worker_id id, title, description, pro_time from fitment_worker where worker_id = ' . $item['worker_id'])->row_array();
					$data['type'] = '1';
				} else {
					$data = $this->db->query('select designer_id id, title, description, pro_time from fitment_designer where designer_id = ' . $item['designer_id'])->row_array();
					$data['type'] = '2';
				}
					$data['public_time'] = $item['public_time'];
					$data['number'] = $item['number'];
					$data['cart_id'] = $item['cart_id'];
					$tmp[$i] = $data;
				++$i;
			}
			$result['carts'] = $tmp;
			return $result;
		}

		public function get_user_center($startindex = 0) {
			$type = get_data_from_cookie('type');
			$id = get_data_from_cookie('id');
			$per_page = $this->config->item('per_page');
			if ($type == 0) {
					//去获取需要装修的人发布的信息
					$sql = "select demand_id, title, description, price, area, 
					public_date from fitment_demands  where user_id = ? limit ?,?";
					$array = array(
						$id, $startindex + "", $per_page
					);
					$data['demands'] = $this->db->query($sql, $array)->result_array();
				} else if ($type == 1) {
					//去获取装修师傅发布的信息
					$sql = "select worker_id id, title, description, pro_time, public_time from fitment_worker where user_id = " . $id . " limit " . $startindex . "," . $per_page . ";";
					$data['demands'] = $this->db->query($sql)->result_array();
				} else if ($type == 2) {
					//去获取设计师发布的信息
					$sql = "select designer_id id, title, description, pro_time, public_time from fitment_designer where user_id = " . $id;
					$data['demands'] = $this->db->query($sql)->result_array();
				}
			return $data;
		}
		/*
			得到行数的总数
		*/
		public function get_count() {
			$type = get_data_from_cookie('type');
			$id = get_data_from_cookie('id');
			$sql = '';
			if ($type == 0) {
				$sql = 'select count(demand_id) a from fitment_demands where user_id = ' . $id;
			} else if ($type == 1) {
				$sql = 'select count(worker_id) a from fitment_worker where user_id = ' . $id;
			} else if ($type == 2) {
				$sql = 'select count(designer_id) a from fitment_designer where user_id = ' . $id;
			}
			return $this->db->query($sql)->row_array()['a'];
		}

		/*
			得到行数的总数
		*/
		public function get_count_fitmentcart_table() {
			$id = get_data_from_cookie('id');
			$sql = 'select count(cart_id) a from fitment_cart where type = 1 and user_id = ' . $id;
			return $this->db->query($sql)->row_array()['a'];
		}

		/*
			得到行数的总数
		*/
		public function get_count_fitment_address_table() {
			$id = get_data_from_cookie('id');
			$sql = 'select count(address_id) a from fitment_address where user_id = ' . $id;
			return $this->db->query($sql)->row_array()['a'];
		}

		 /*
			得到一行数据
		*/
		public function get_message_row($id = 0) {
			$type = get_data_from_cookie('type');
			$sql = '';
			if ($type == 0) {
				$sql = 'select demand_id id, title, description, area, price from fitment_demands where demand_id = ' . $id;
			} else if ($type == 1) {
				$sql = 'select worker_id id, title, description, pro_time from fitment_worker where worker_id = ' . $id;
				$sql_re = 'select image_id, res_url from fitment_res where worker_id = ' . $id;
				$data = $this->db->query($sql)->row_array();
				$data['images'] = $this->db->query($sql_re)->result_array();
				return $data;
			} else if ($type == 2) {
				$sql = 'select designer_id id, title, description, pro_time from fitment_designer where designer_id = ' . $id;
			}
			return $this->db->query($sql)->row_array();
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
				return "发布成功";
			} else {
				return "发布失败";
			}
		}
        
        /*
		需要装修的人的 删除需求
		*/
		public function delete_message($demand_id = 0) {
			$this->db->query('delete from fitment_demands where demand_id=' . $demand_id);
			if ($this->db->affected_rows() > 0) {
				return "删除成功";
				//setcookie('result', 'shanchu', time() + 60 * 60 * 24 * 7, "/");
			} else {
				return "删除失败";
				//setcookie('result', '删除失败', time() + 60 * 60 * 24 * 7, "/");
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
			$this->db->query($sql);
			if ($this->db->affected_rows() > 0) {
				return "更新成功";
			} else {
				return "更新失败";
			}
		}

			/*
		装修师傅 发布的项目
		*/
		public function insert_worker() {
		  	$id = get_data_from_cookie('id');
			$array = array(
				'user_id'=>$id,
				'title'=>$this->input->post('title'),
				'description'=>$this->input->post('description'),
				'public_time'=>date('Y-m-d H:i:s'),
				'pro_time'=>$this->input->post('pro_time')
			);

			$this->db->trans_start();   //开启事务
			$insert_worker_result = $this->db->insert('fitment_worker', $array);
			if ($insert_worker_result) {
				$worker_id = $this->db->query('select max(worker_id) worker_id from fitment_worker')->row_array()['worker_id'];

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
				  			$type = substr($file['type'][$i], 0, 5) == 'image' ? 0 : 1;
				  			$exp_name = explode('.', $name);
				  			$suffix = $exp_name[count($exp_name) - 1];
				  			$uploadresult = $uploadMgr->putFile($token, $i . 'fitment_' . mt_rand() . time() . '.' . $suffix, $file['tmp_name'][$i]);
					  		$upload = array(
					  			'worker_id'=>$worker_id,
					  			'type'=>$type,
					  			'res_url'=>$uploadresult[0]['key'],
					  			'res_time'=>date('Y-m-d H:i:s')
					  			);
					  		if (!$this->db->insert('fitment_res', $upload)) {
					  			//$result['result'] = '有些图片上传失败';
					  		}
				  		 } 
				  }
				  $this->db->trans_complete(); //结束事务
				  if ($this->db->trans_status() === false) {
				  	//setcookie('result', '发布失败', time() + 60 * 60 * 24 * 7, "/");
				  	return "发布失败";
				  }
					//setcookie('result', '发布成功', time() + 60 * 60 * 24 * 7, "/");
				  	return "发布成功";
				} else {
					//setcookie('result', '发布失败', time() + 60 * 60 * 24 * 7, "/");
					return "发布成功";
				}
		}

		public function delete_worker($id) {
			  $accessKey = $this->config->item('qiniu_ak');
			  $secretKey = $this->config->item('qiniu_sk');
			  //初始化Auth状态
			  $auth = new Auth($accessKey, $secretKey);
			  //初始化BucketManager
			  $bucketMgr = new BucketManager($auth);
			  $bucket = $this->config->item('qiniu_bucket');
			  $query = $this->db->query('select res_url from fitment_res where worker_id=' . $id);
			  $key = $query->row_array()['res_url'];
			  //删除$bucket 中的文件 $key
			  $err = $bucketMgr->delete($bucket, $key);
			  if ($err !== null) {
			      var_dump($err);
			  } else {
			      $this->db->query('delete from fitment_res where worker_id=' . $id);
				if ($this->db->affected_rows() >= 0) {
					$this->db->query('delete from fitment_worker where worker_id=' . $id);
					setcookie('result', '删除成功', time() + 60 * 60 * 24 * 7, "/");
				} else {
					setcookie('result', '删除失败', time() + 60 * 60 * 24 * 7, "/");
				}
			  }
		}

		public function update_worker($id) {
			//七牛 先上传图片再删除之前的
			 $file = $_FILES['userfile'];
			 $res_all = $this->input->post('ids');
			  $accessKey = $this->config->item('qiniu_ak');
			  $secretKey = $this->config->item('qiniu_sk');
			  $auth = new Auth($accessKey, $secretKey);
			  $bucket = $this->config->item('qiniu_bucket');
			  // 生成上传Token
			  $token = $auth->uploadToken($bucket);
			  // 构建 UploadManager 对象
			  $uploadMgr = new UploadManager();
			  for ($i=0; $i < count($file['name']); $i++) {
			  		$name = $file['name'][$i];
			  		var_dump("" != $name);
			  		exit;
			  		if ("" != $name) {
			  			$type = substr($file['type'][$i], 0, 5) == 'image' ? 0 : 1;
			  			$exp_name = explode('.', $name);
			  			$suffix = $exp_name[count($exp_name) - 1];
			  			$uploadresult = $uploadMgr->putFile($token, $i . 'fitment_' . mt_rand() . time() . '.' . $suffix, $file['tmp_name'][$i]);
				  		if ($uploadresult[0]['error'] != null) {
				  			return false;
				  		}
				  		$res = $res_all[$i];
				  		$res_array = explode(',', $res);
				  		$res_id = $res_array[0];
				  		$res_url = $res_array[1];
			  			//初始化BucketManager
						  $bucketMgr = new BucketManager($auth);
						  //删除$bucket 中的文件 $key
						  $err = $bucketMgr->delete($bucket, $res_url);
						  if ($err !== null) {
						      var_dump($err);
						  } else {
						  	$array = array('type'=>$type, 'res_url'=>$uploadresult[0]['key'], 'res_time'=>date("Y-m-d H:i:s"));
							$sql = $this->db->update_string('fitment_res', $array, 'image_id=' . $res_id);
							$updateres = $this->db->query($sql);
							if ($updateres) {
								$title = $this->input->post('title');
								$description = $this->input->post('description');
								$pro_time = $this->input->post('pro_time');

								$array = array('title'=>$title, 'description'=>$description, 'pro_time'=>$pro_time, 'public_time'=>date("Y-m-d H:i:s"));
								$sql = $this->db->update_string('fitment_worker', $array, 'worker_id=' . $id);
								$updateresult = $this->db->query($sql);
								if ($updateresult) {
									setcookie('result', '更新成功', time() + 60 * 60 * 24 * 7, '/');
									return true;
								} else {
									return false;
								}
							}
						  }
			  }
			}
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
		}

		public function delete_designer($id) {
			$this->db->trans_start();
			$this->db->query('delete from fitment_demands where designer_id=' . $id);
			$this->db->query('delete from fitment_res where designer_id=' . $id);
			$this->db->trans_complete();
			if ($this->db->trans_status === false) {
				setcookie('result', '删除失败', time() + 60 * 60 * 24 * 7, "/");
			} else {
				setcookie('result', '删除成功', time() + 60 * 60 * 24 * 7, "/");
			}
		}
	}