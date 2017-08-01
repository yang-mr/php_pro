<?php
	function __autoload($name) {
		require_once $name . ".class.php";
	}

	$student = new Student(new Person());
	$student->writeName();

	print_r(get_class_methods('Student'));
	print_r(get_class_vars('Person'));

	$person = getPerson();
	$method = 'getName';
	print_r($person->$method);