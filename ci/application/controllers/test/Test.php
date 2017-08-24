<?php
	class Test extends CI_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->model('blog_model', 'model');
			$this->load->library('pagination');
			$this->load->library('parser');
			$this->load->library('table');
			$this->load->library('unit_test');
			$this->load->library('zip');
			$this->load->helper('date');
		}

		public function test() {
		  /* $redis = new Redis();
		   $redis->connect('127.0.0.1',6379);
		   $redis->set('string','hello redis');
		   echo $redis->get('string');*/
		   // phpinfo();
		   // 
		   // 
		   
			//连接
			$mem = new Memcache;
			$mem->connect("127.0.0.1", 11211);
			//保存数据 
		// 	$mem->set('key1', false);    
		    or
		// 	$mem->add('key3', 'my name is key3');

			$data = array('key1', 'key2');
			//取数据  单个取或者多个取
		//	$val = $mem->get('key1', $flag);
			
			//replace 替换数据
			$mem->set('count', 1);
			$mem->replace('key1', '我是新的');

			//delete 删除数据   5:数据在5秒之内被删除
			//$mem->delete('key1', 5);
			//清楚所有数据
			//$mem->flush();
			$var = $mem->get('key1');
			//在基础上增加3
			//$mem->increment('count', 3);
			//减少
			$mem->decrement('count', -1);
			var_dump($mem->get('key3'));
			exit;
/*
			//替换数据
			$mem->replace('key1', 'This is replace value', 0, 60);
			$val = $mem->get('key1');
			echo "Get key1 value: " . $val . "<br />";

			//保存数组
			$arr = array('aaa', 'bbb', 'ccc', 'ddd');
			$mem->set('key2', $arr, 0, 60);
			$val2 = $mem->get('key2');
			echo "Get key2 value: ";
			print_r($val2);
			echo "<br />";

			//删除数据
			$mem->delete('key1');
			$val = $mem->get('key1');
			echo "Get key1 value: " . $val . "<br />";

			//清除所有数据
			$mem->flush();
			$val2 = $mem->get('key2');
			echo "Get key2 value: ";
			print_r($val2);
			echo "<br />";*/

			//关闭连接
			$mem->close();
		}

		public function curl() {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'www.baidu.com');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			//var_dump($output);
			$info = curl_getinfo($ch);
			var_dump($info);
			curl_close($ch);
		}
	}