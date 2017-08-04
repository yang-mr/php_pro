<?php
	$action = $_POST['action'];
	switch ($action) {
		case 'createwant':
			echo createwant();
			break;
		default:
			break;
	}

	function createwant() {
		$title = $_POST['title'];
		$des = $_POST['des'];
		$area = $_POST['area'];
		$price = $_POST['price'];
		$file = $_POST['file'];
		var_dump($file);
		$move = move_uploaded_file($file, "/public");
		var_dump($move);
		var_dump($_FILES['file']['error']);
		return 1;
	}