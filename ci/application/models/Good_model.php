<?php
	class Good_model extends Base_model {
		public  function __construct() {
			parent::__construct();
		}
		/*
			得到一个商品详情
		*/
		public function get_good($id = 0, $type = 1) {
			$result = array();
			$sql = '';
			if ($type == 0) {
				$sql = "select count(demand_id) a from fitment_demands";
			} else if ($type == 1) {
				$sql = "select worker_id id, title, description, pro_time, public_time from fitment_worker where worker_id = " . $id;
				$result = $this->db->query($sql)->row_array();
				$sql = "select res_url from fitment_res where worker_id = " . $id;
				$result['images'] = $this->db->query($sql)->result_array();
			} else if ($type == 2) {
				$sql = 'select count(designer_id) a from fitment_designer';
			}
			$result['type'] = $type;
			return $result;
		}

		public function add_cart($id = 0, $type = 1) {
			$user_id = get_data_from_cookie('id');
			if ($type == 0) {
				$sql = "select count(demand_id) a from fitment_demands";
			} else if ($type == 1) {
				$array = array(
					'user_id'=>$user_id,
					'worker_id'=>$id,
					'public_time'=>date('Y-m-d H:i:sa')
				);

				$result = $this->db->insert('fitment_cart', $array);
				if ($result) {
					return '加入购物车成功';
				} else {
					return '加入购物车成功';
				}
			} else if ($type == 2) {
				$array = array(
					'user_id'=>$user_id,
					'designer_id'=>$id,
					'public_time'=>date('Y-m-d H:i:sa'),
				);

				$result = $this->db->insert('fitment_cart', $array);
				if ($result) {
					return '加入购物车成功';
				} else {
					return '加入购物车成功';
				}
			}
		}

		public function change_cart_count() {
			$cart_id = $this->input->post('cart_id');
			$number = $this->input->post('count');
			if ($cart_id != null && $number != null) {
				$sql = 'update fitment_cart set number = ' . $number . ' where cart_id = ' . $cart_id;
				$result = $this->db->query($sql);
				// var_dump($result);
				// exit;
				if ($result) {
					return '操作成功';
				} else {
					return '操作失败';
				}
			}
		}
	}