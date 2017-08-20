<?php
	class Base_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->database();
		}

		/*
			得到行数的总数
		*/
		public function get_count_by_tablename($type = 0) {
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
	}