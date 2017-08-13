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
			/*$query = $this->db->query("select * from blog where id = 1");
			foreach ($query->result() as $row) {
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

			/*$query = $this->db->get('blog');
			foreach ($query->result() as $row) {
				echo $row->title;
			}*/

			/*$sql = $this->db->get_compiled_select('blog', false);
			var_dump($sql);

			echo $this->db->select('title, des')->get_compiled_select();*/

			/*$this->db->select_sum('num');
			$query = $this->db->get('blog');

			var_dump($query->result());*/

			/*$this->db->select('title, des, num');
			$this->db->from('blog');
			$query = $this->db->get();

			foreach ($query->result() as $row) {
				echo $row->title;
				echo $row->num;
			}*/

		/*	$array = array('kfdk', 'kjfdk', '8989');
			$sql = $this->db->where_in('title', $array);
			var_dump($sql);*/

			//echo $this->db->count_all('blog');

			/*$array = array(array('title'=>'t3', 'des'=>'d3'), array('title'=>'t4', 'des'=>'d4'));
			$this->db->insert_batch('blog', $array);*/

		/*	$array = array('title'=>'tt');
			$this->db->set($array);
			$this->db->where('id', 1);
			$this->db->update('blog');*/

			//$this->db->delete('blog', 'id=2');
			$query = $this->db->select('title')
				  ->where('num>', 9)
				   ->limit(1, 0)
				   ->get('blog');
			foreach ($query->result() as $row) {
				echo $row->title;
			}
		}
	}