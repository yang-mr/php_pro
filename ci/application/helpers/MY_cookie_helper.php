<?php
	function get_data_from_cookie($name) {
		$value = get_cookie($name);
		if ($value != null) {
			return $value;
		} else {
			delete_cookie('username');
			delete_cookie('id');
			delete_cookie('type');
			header("Location:" . base_url() . "user/login");
			exit();
		}
	}

	function is_login() {
		$value = get_cookie("username");
		if ($value != null) {
			return true;
		} else {
			return false;
		}
	}