<?php
	class Blog_model extends CI_Model {
		public function __construct() {
			parent::__construct();
			$this->load->database();
			$this->load->library('blog');
		}

		public function insert_blog() {
			echo "my name is insert_blog";
		}

		public function get_blog() {
			$query = $this->db->query("select * from blog where id = 1");
			/*foreach ($query->result() as $row) {
				echo $row->title . PHP_EOL;
				echo $row->des . PHP_EOL;
				echo $row->id . PHP_EOL;
			}*/

		/*	foreach ($query->result_array() as $row) {
				echo $row['title'] . PHP_EOL;
				echo $row['des'] . PHP_EOL;
				echo $row['id'] . PHP_EOL;
			}*/
		/*	$query->data_seek(1);
			$row = $query->unbuffered_row('Blog');

			var_dump($row->title);
			
			var_dump($row->title);*/

		/*	while ($row = $query->unbuffered_row()) {
				echo $row->title . PHP_EOL;
			}

			echo $query->num_rows();*/

			/*$str = $this->db->last_query();
			echo $str;	*/
			
		}
	}