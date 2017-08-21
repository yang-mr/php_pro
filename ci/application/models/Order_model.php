<?php
	class Order_model extends Base_model {
		public  function __construct() {
			parent::__construct();
			$this->
		}
		/*
			生成订单
		*/
		public function create_order() {
			$carts = $this->input->post('carts');
			foreach (var $i = 0; $i < count($carts); $i++) {
				$sql = 'select worker_id, designer_id, number from fitment_cart where cart_id = ' . $carts[i];
				$row_array = $this->db->query($sql)->row_array();
				if ($row_array['worker_id'] != null) {
					
				} else {
					
				}
			}
			return $result;
		}
	}