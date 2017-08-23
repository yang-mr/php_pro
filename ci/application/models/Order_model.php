<?php
	class Order_model extends Base_model {
		public  function __construct() {
			parent::__construct();
		}

		public function get_default_address($address_id = 0) {
			$id = get_data_from_cookie("id");
			if ($address_id == 0) {
				$sql = "select address_id id, name, phone, zip, province, city, address_detail from fitment_address where user_id = " . $id . " and is_default = 1";
			} else {
				$sql = "select address_id id, name, phone, zip, province, city, address_detail from fitment_address where address_id = " . $address_id;
			}
			return $this->db->query($sql)->row_array();
		}
		/*
			生成订单
		*/
		public function create_order($address_id = 0, $tmpcarts) {
			$carts = explode("-", $tmpcarts);
			$tmp = array();
			for ($i=0; $i<count($carts); $i++) {
				$sql = 'select worker_id, designer_id, number from fitment_cart where cart_id = ' . $carts[$i];
				$row_array = $this->db->query($sql)->row_array();

				if ($row_array['worker_id'] != null) {
					$tmpsql = 'select title, description, pro_time from fitment_worker where worker_id = ' . $row_array['worker_id'];
				} else if($row_array['designer_id'] != null){
					$tmpsql = 'select title, description, pro_time from fitment_designer where designer_id = ' . $row_array['designer_id'];
				}
					$data = $this->db->query($tmpsql)->row_array();
					$data['number'] = $row_array['number'];
					$data['cart_id'] = $carts[$i];
					$tmp[$i] = $data;
					
			}
			$result['carts'] = $tmp;
			$result['cartids'] = $tmpcarts;
			$result['address'] = $this->get_default_address($address_id);
			
			return $result;
		}

		/*
			生成订单
		*/
		public function create_order_ok($address_id = 0, $tmpcarts) {
			$id = get_data_from_cookie('id');
			$carts = explode("-", $tmpcarts);
			$order_money = 0.00;
			//计算订单金额
			for ($i=0; $i<count($carts); $i++) {
					$sql = 'select worker_id, designer_id, number from fitment_cart where cart_id = ' . $carts[$i];
					$row_result = $this->db->query($sql)->row_array();
					if ($row_result['worker_id'] != null) {
						$money = $this->db->query('select pro_time from fitment_worker where worker_id = ' . $row_result['worker_id'])->row_array()['pro_time'];
						$order_money += $money * $row_result['number'];
					} else if ($row_result['designer_id'] != null) {
						$money = $this->db->query('select pro_time from fitment_designer where designer_id = ' . $row_result['designer_id'])->row_array()['pro_time'];
						$order_money += $money * $row_result['number'];
					}
				}
			$data = array(
				'user_id'=>$id,
				'address_id'=>$address_id,
				'status'=>0,
				'order_money'=>$order_money,
				'create_time'=>date('Y-m-d H:i:sa')
				);

			$resultinsert = $this->db->insert('fitment_order', $data);
			if ($resultinsert) {
				$order_id = $this->db->query("select max(order_id) order_id from fitment_order")->row_array()['order_id'];

				for ($i=0; $i<count($carts); $i++) {
					$sql = 'update fitment_cart set type = 0, order_id = ' . $order_id . ' where cart_id = ' . $carts[$i];
					$this->db->query($sql);
					if ($this->db->affected_rows() == 0) {
						return '生成订单失败';
					}
				}
			}
			return '生成订单成功' . $order_id;
		}

		/*
			订单详情
		*/
		public function order_detail($order_id) {
				$cartids = $this->db->query("select worker_id, designer_id, number from fitment_cart where order_id = " . $order_id)->result_array();
				$i = 0;
				$temp = array();
				foreach ($cartids as $item) {
					$item_worker_id = $item['worker_id'];
					$item_designer_id = $item['designer_id'];
					$tmpdata = array();
					if ($item_worker_id != null) {
						$sql = "select title, description, pro_time from fitment_worker where worker_id = " . $item_worker_id;
					} else if ($item_designer_id != null) {
						$sql = "select title, description, pro_time from fitment_designer where worker_id = " . $item_designer_id;
					}
					$tmpdata = $this->db->query($sql)->row_array();
					$tmpdata['number'] = $item['number'];
					$temp[$i] = $tmpdata;
					++$i;
				}
				$order_message = $this->db->query('select address_id, create_time, order_money, status from fitment_order where order_id = ' . $order_id)->row_array();
				$result['carts'] = $temp;
				$result['create_time'] = $order_message['create_time'];
				$result['order_money'] = $order_message['order_money'];
				$result['status'] = $order_message['status'];
				
				$address_message = $this->db->query('select name, phone, zip, province, city, address_detail from fitment_address where address_id = ' . $order_message['address_id'])->row_array();
				$result['address'] = $address_message;		
				$result['order_id'] = $order_id;		
				return $result;
		}

		//取消订单
		public function cancel_order($order_id) {
			$this->db->query('update fitment_order set status = 2 where order_id = ' . $order_id);
			if ($this->db->affected_rows() > 0) {
				return '取消订单成功';
			}
			return '取消订单失败';
		}

		//取消订单
		public function get_rows_from_order($status) {
			$id = get_data_from_cookie('id');
			return $this->db->query('select count(order_id) ids from fitment_order where status = ' . $status . ' and user_id = ' . $id)->row_array()['ids'];
		}


		/*
			status
				0 待付款
				1 已付款
				2 已取消
				3 已失效
		 */
		public function user_order($status, $page) {
			$id = get_data_from_cookie('id');
			$per_page = $this->config->item('per_page');

			$order_result = $this->db->query('select order_id, create_time, status, order_money from fitment_order where user_id = ' . $id . ' and status = ' . $status . ' limit ' . $page . ',' . $per_page)->result_array();
			$tmp = array();
			for($i = 0; $i < count($order_result); $i++) {
				$temp = $order_result[$i];
			
				$order_id = $order_result[$i]['order_id'];
				$cart_result = $this->db->query('select worker_id, designer_id, number from fitment_cart where order_id = ' . $order_id)->result_array();
				$carttmp = array();
				for($j = 0; $j < count($cart_result); $j++) {
					$worker_id = $cart_result[$j]['worker_id'];
					$designer_id = $cart_result[$j]['designer_id'];
					if ($worker_id != null) {
						 $data = $this->db->query('select title, description, pro_time from fitment_worker where worker_id = ' . $worker_id)->row_array();
						 $data['number'] = $cart_result[$j]['number'];
						 $carttmp[$j] = $data;
					}
				}
				$temp['carts'] = $carttmp;
				$tmp[$i] = $temp;
			}
			$result['orders'] = $tmp;
			/*var_dump($result);
			exit;*/
			return $result;
		}
	}
