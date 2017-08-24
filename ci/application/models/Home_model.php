<?php
	require_once "Base_model.php";

	class Home_model extends Base_model {
		public  function __construct() {
			parent::__construct();
		}

		/*
			得到行数的总数
		*/
		public function get_count($type = 0) {
			$sql = '';
			if ($type == 0) {
				$sql = "select count(demand_id) a from fitment_demands";
			} else if ($type == 1) {
				$sql = "select count(worker_id) a from fitment_worker";
			} else if ($type == 2) {
				$sql = 'select count(designer_id) a from fitment_designer';
			}
			return $this->db->query($sql)->row_array()['a'];
		}
		
		public function index($demand_startpage=0, $type=0) {
			$mem = parent::getMemcache();
			$result = $mem->get('index');
			if ($result) {
				var_dump($result);
				exit;
				return $result;
			}

			$per_page = $this->config->item('per_page');
			$result = array();
			if ($type == 0) {
				$sql1 = "select demand_id id, title, description, price, area, public_date from fitment_demands limit " . $demand_startpage . "," . $per_page;
				$result['demands'] = $this->db->query($sql1)->result_array();

				$sql2 = "select worker_id id, title, description, pro_time, public_time from fitment_worker limit " . 0 . "," . $per_page;
				$result['workers'] = $this->db->query($sql2)->result_array();
			} else if($type == 1) {
				$sql1 = "select demand_id id, title, description, price, area, public_date from fitment_demands limit " . 0 . "," . $per_page;
				$result['demands'] = $this->db->query($sql1)->result_array();

				$sql2 = "select worker_id id, title, description, pro_time, public_time from fitment_worker limit " . $demand_startpage . "," . $per_page;
				$result['workers'] = $this->db->query($sql2)->result_array();
			}
			$mem->set('index', $result);
			return $result;
		}
	}