<?php
/**
 * Short description for setup-ing.php
 *
 * Copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 *
 * @package setup-ing
 * @author KuoE0 <kuoe0.tw@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 */

include_once 'db_con.php';

if (isset($_POST['submit'])) {

	$title = $_POST['title'];
	$username = $_POST['username'];
	$passwd = sha1($_POST['username'] . $_POST['password']);

	$sql = "INSERT INTO `attributes` (`attr`, `value`) VALUES (:attr, :value)";
	$stmt = $db->prepare($sql);
	// insert title
	$stmt->execute(array(':attr' => 'title', ':value' => $title));
	// insert username
	$stmt->execute(array(':attr' => 'username', ':value' => $username));
	// insert password
	$stmt->execute(array(':attr' => 'password', ':value' => $passwd));

	// parse student list
	if ($_FILES['student_list']['error'] == 0) {
		$filename = $_FILES['student_list']['name'];
		$type = $_FILES['student_list']['type'];
		$tmp_name = $_FILES['student_list']['tmp_name'];


		if (($f = fopen($tmp_name, 'r')) != FALSE) {
			// for the large csv file
			set_time_limit(0);
			$sql = "INSERT INTO `students` (`student_id`, `name`) VALUES (:student_id, :name)";
			$stmt = $db->prepare($sql);

			// read line by line in csv file
			while (($data = fgetcsv($f)) != FALSE) {
				$n = count($data);
				// insert student info
				$stmt->execute(array(':student_id' => $data[0], ':name' => $data[1]));
			}


		}

	}

	// mark setup process be ready to prevent setup again
	$sql = "INSERT INTO `attributes` (`attr`, `value`) VALUES (:attr, :value)";
	$stmt = $db->prepare($sql);
	$stmt->execute(array(':attr' => 'setup', ':value' => 'yes'));
}

?>

