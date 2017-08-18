<?php
	class Home_model extends CI_Model{
		public  function __construct() {
			parent::__construct();
        			$this->load->database();
		}
		
		public function index($demand_startpage=0, $worker_startpage=0, $designer_startpage=0) {
			$per_page = $this->config->item('per_page');
			$sql1 = "select title, description, price, area, public_date from fitment_demands limit " . $designer_startpage . "," . $per_page;
			$query_demands = $this->db->query($sql1);
			$result_demands = $query_demands->result_array();
			$demands_sum = count($result_demands);
			$result_demands['sum'] = $demands_sum;
			return $result_demands;
		}
	}