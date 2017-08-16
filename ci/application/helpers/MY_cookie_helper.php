<?php
	function get_data_from_cookie($name) {
		$value = get_cookie($name);
		if ($value != null) {
			return $value;
		} else {
			delete_cookie('username');
			delete_cookie('id');
			delete_cookie('type');
			header("Location:./login");
		}
	}