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
include_once 'function.php';

if (isset($_POST['submit'])) {

	$title = $_POST['title'];
	$url = $_POST['url'];
	$username = $_POST['username'];
	$passwd = sha1($_POST['username'] . $_POST['password']);

	$sql = "SELECT * FROM `attributes` WHERE `attr` = 'setup'";
	$stmt = $db->prepare($sql);
	$stmt->execute();
	if (!$stmt->fetch()) {
		cleanup_db($db);
	}


	$sql = "INSERT INTO `attributes` (`attr`, `value`) VALUES (:attr, :value)";
	$stmt = $db->prepare($sql);
	// insert title
	$stmt->execute(array(':attr' => 'title', ':value' => $title));
	// insert url
	$stmt->execute(array(':attr' => 'url', ':value' => $url));
	// insert username
	$stmt->execute(array(':attr' => 'username', ':value' => $username));
	// insert password
	$stmt->execute(array(':attr' => 'password', ':value' => $passwd));

	// parse presenter list
	if ($_FILES['presenter_list']['error'] == 0) {
		$filename = $_FILES['presenter_list']['name'];
		$type = $_FILES['presenter_list']['type'];
		$tmp_name = $_FILES['presenter_list']['tmp_name'];


		if (($f = fopen($tmp_name, 'r')) != FALSE) {
			// for the large csv file
			set_time_limit(0);
			$sql = "INSERT INTO `presenters` (`presenter_id`, `group_id`, `name`, `registered`) VALUES (:presenter_id, :group_id, :name, 0)";
			$stmt = $db->prepare($sql);

			// read line by line in csv file
			while (($data_row = fgetcsv($f)) != FALSE) {
				$n = count($data_row);
				// insert presenter info
				$data[':presenter_id'] = $data_row[0];
				$data[':name'] = $data_row[1];
				$data[':group_id'] = $n == 3 ? $data_row[2] : $data_row[0];

				$stmt->execute($data);

			}

		}

	}

	// parse timeslots list
	if (isset($_FILES['timeslot_list']) && $_FILES['timeslot_list']['error'] == 0) {
		$filename = $_FILES['timeslot_list']['name'];
		$type = $_FILES['timeslot_list']['type'];
		$tmp_name = $_FILES['timeslot_list']['tmp_name'];

		if (($f = fopen($tmp_name, 'r')) != FALSE) {
			// for the large csv file
			set_time_limit(0);
			$sql = "INSERT INTO `timeslots` (`date`, `begin_time`, `end_time`, `slice`, `occupied`) VALUES (:date, :begin_time, :end_time, :slice, 0)";
			$stmt = $db->prepare($sql);

			// read line by line in csv file
			while (($data = fgetcsv($f)) != FALSE) {
				// insert group info
				$date = $data[0] == '' ? 'NULL' : $data[0];
				$begin_time = $data[1] == '' ? 'NULL' : $data[1];
				$end_time = $data[2] == '' ? 'NULL' : $data[2];
				$quota = $data[3];

				for ($i = 0; $i < $quota; ++$i) {
					$stmt->execute(array(':date' => $date, ':begin_time' => $begin_time, ':end_time' => $end_time, ':slice' => ($i + 1)));
				}
			}

		}
	}
	else {
		$rule_cnt = $_POST['rule-cnt'];

		for ($i = 0; $i < $rule_cnt; ++$i) {
			if (!isset($_POST['quota-' . $i])) {
				continue;
			}

			$begin_date = $_POST['begin-date-' . $i] == '' ? 'NULL' : new DateTime($_POST['begin-date-' . $i]);
			$begin_time = $_POST['begin-time-' . $i] == '' ? 'NULL' : new DateTime($_POST['begin-time-' . $i]);
			$end_date = isset($_POST['end-date-' . $i]) && $_POST['end-date-' . $i] != '' ? new DateTime($_POST['end-date-' . $i]) : 'NULL';
			$end_time = $_POST['end-time-' . $i] == '' ? 'NULL' : new DateTime($_POST['end-time-' . $i]);
			$repeat_mode = $_POST['repeat-' .$i];
			$quota = $_POST['quota-' .$i];

			$sql = "INSERT INTO `timeslots` (`date`, `begin_time`, `end_time`, `slice`, `occupied`) VALUES (:date, :begin_time, :end_time, :slice, 0)";
			$stmt = $db->prepare($sql);
			$interval = 'P1D';

			if ($repeat_mode == 'weekly') {
				$interval = 'P7D';
			}
			else if ($repeat_mode == 'monthly') {
				$interval = 'P1M';
			}
			else if ($repeat_mode == 'none') {
				$end_date = clone $begin_date;
			}

			while ($begin_date <= $end_date) {

				for ($j = 0; $j < $quota; ++$j) {
					$stmt->execute(array(':date' => $begin_date->format('Y-m-d'), ':begin_time' => $begin_time->format('H:i'), ':end_time' => $end_time->format('H:i'), ':slice' => ($j + 1)));
				}

				$begin_date->add(new DateInterval($interval));
			}

		}
	}
	// mark setup process be ready to prevent setup again
	$sql = "INSERT INTO `attributes` (`attr`, `value`) VALUES (:attr, :value)";
	$stmt = $db->prepare($sql);
	$stmt->execute(array(':attr' => 'setup', ':value' => 'yes'));

	echo 'Setup successfully! Redirect after 5 sec...';
}
else {
	echo 'Setup failed! Redirect after 5 sec...';
	cleanup_db();
}

header("Refresh: 5; URL=" . $url);

?>

