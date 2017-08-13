<?php
	class Blog extends CI_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->model('blog_model', 'model');
			$this->load->library('pagination');
			$this->load->library('parser');
			$this->load->library('table');
			$this->load->library('unit_test');
			$this->load->library('zip');
		}

		public function create_blog() {
		//	$this->model->insert_blog();
			/*$hash = password_hash('123456', PASSWORD_DEFAULT);
			var_dump(password_get_info($hash));
			var_dump(password_verify('123456', $hash));
			echo $hash;*/

			//$this->model->get_blog();
			/*$config['base_url'] = 'www.baidu.com';
			$config['total_rows'] = 200;
			$config['per_page'] = 20;*/
/*$config['base_url'] = '';
$config['total_rows'] = 20;
$config['suffix'] = '.html';
$config['per_page'] = 2;
$config['num_links'] = 2;
			$this->pagination->initialize($config);
			echo $this->pagination->create_links();*/
			/*$data = array(
				'title'=>'my name is title',
				'des'=>'my name is des',
				'lists'=>array(
					array('title'=>'my name is title', 'des'=>'my name is des'),
					array('title'=>'my name is title', 'des'=>'my name is des')
				)
			);
			$this->parser->parse('blog', $data);*/
/*
			$template = 'hello , {firstname}{lastname}';
			$data=array(
				'firstname'=>'yang',
				'lastname'=>'wei'
			);

			var_dump($this->parser->parse_string($template, $data));
			$this->load->view('blog');*/
			/*$data=array(
				array('name', 'age', 'word'),
				array('rose', 13, 'it'),
				array('jack', 15, 'it2')
			);
			echo $this->table->generate($data);*/

			/*$this->table->set_heading('name', 'age', 'word');
			$this->table->add_row(array('rose', 12, '87'));
			$this->table->add_row('rose1', 38, 'jfd');

			$template = array(
				'table_open' => '<table border="1" cellpadding="2" cellspacing="1" class="mytable">'
			);
			$this->table->set_template($template);
			$this->table->set_caption('my name title table');
			echo $this->table->generate();*/
/*$test = 1 + 1;

$expected_result = 2;

$test_name = 'Adds one plus one';
 $this->unit->run($test, $expected_result, $test_name);
  $this->unit->run(null, 'is_null');
  	//echo $this->unit->result();
  	$this->unit->use_strict(true);
    if (1 == TRUE) echo 'This evaluates as true';*/

    	$name = 'data.txt';
    	$content = 'fdkjk';
    	$this->zip->add_data($name, $content);
    	var_dump( $this->zip->archive('./upload/my_backup.zip'));
    	//$this->zip->download('my_kjfd.zip');
		}

		public function test() {
			/*$this->load->helper('array');
			$data=array(
				'title'=>'my title',
				'des'=>'my des',
				'age'=>212
			);
			echo element('title1', $data, 'jfk');
			var_dump(elements(array('title', 'des', 'sex'), $data, 'default'));*/

			/*$this->load->helper('captcha');
			$cfg = array(
				'img_path'=>'./captcha/',
				'img_url'   => 'http://localhost/index.php/captcha/'
			);
			$cap = create_captcha($cfg);
			var_dump($cap);*/

			$this->load->helper('form');
			echo form_input('username', 'jack');

			$this->load->helper('url');
			echo base_url('/capcha');
			echo index_page();
		}
	}