<?php
	$action = $_POST['action'];
	switch ($action) {
		case 'createwant':
			echo createwant();
			break;
		
		default:
			break;
	}

	private function createwant() {
		$title = $_POST['title'];
		$des = $_POST['des'];
		$area = $_POST['area'];
		$price = $_POST['price'];

		
	}