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

			$data = array(
				'user_id'=>$id,
				'address_id'=>$address_id,
				'status'=>0,
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
			return '生成订单成功';
		}
	}
