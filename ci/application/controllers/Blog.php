<?php
	class Blog extends CI_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->model('blog_model', 'model');
		}

		public function create_blog() {
		//	$this->model->insert_blog();
			/*$hash = password_hash('123456', PASSWORD_DEFAULT);
			var_dump(password_get_info($hash));
			var_dump(password_verify('123456', $hash));
			echo $hash;*/

			$this->model->get_blog();
		}

		/*public function _output($output) {
			echo $output . "_output";
		}

		private function not() {
			echo "private";
		}*/
	}