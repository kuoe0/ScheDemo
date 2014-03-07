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
include_once 'global.php';
include_once 'function.php';

if (isset($_POST['submit'])) {

	// get basic info
	$title = $_POST['title'];
	$url = $_POST['url'];
	$username = $_POST['username'];
	$passwd = sha1($_POST['username'] . $_POST['password']);

	$start_opening_date = $_POST['start-opening-date'];
	$start_opening_time = $_POST['start-opening-time'];
	$end_opening_date = $_POST['end-opening-date'];
	$end_opening_time = $_POST['end-opening-time'];

	// concat date and time
	$start_opening = ($start_opening_date != '' ? $start_opening_date : '') . ($start_opening_time != '' ? (' ' . $start_opening_time) : '');
	$end_opening = ($end_opening_date != '' ? $end_opening_date : '') . ($end_opening_time != '' ? (' ' . $end_opening_time) : '');


	// if no setup attr, clean up database
	try {
		$sql = "SELECT * FROM `attributes` WHERE `attr` = 'setup'";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		if (!$stmt->fetch()) {
			cleanup_db($db);
		}
	}
	catch (Exception $e) {
		cleanup_db($db);
		echo json_encode(array('status' => 'fail', 'errorMsg' => $e->getMessage()));
		exit;
	}

	// attributes insertion
	try {
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

		if ($begin_opening != '') {
			$stmt->execute(array(':attr' => 'begin-opening', ':value' => $begin_opening));
		}

		if ($end_opening != '') {
			$stmt->execute(array(':attr' => 'end-opening', ':value' => $end_opening));
		}
	}
	catch (Exception $e) {
		cleanup_db($db);
		echo json_encode(array('status' => 'fail', 'errorMsg' => $e->getMessage()));
		exit;
	}

	// parse presenter list
	if ($_FILES['presenter-list']['error'] == 0) {

		$filename = $_FILES['presenter-list']['name'];
		$type = $_FILES['presenter-list']['type'];
		$tmp_name = $_FILES['presenter-list']['tmp_name'];

		if (($f = fopen($tmp_name, 'r')) != FALSE) {
			// for the large csv file
			set_time_limit(0);

			try {
				$sql = "INSERT INTO `presenters` (`presenter_id`, `group_id`, `name`) VALUES (:presenter_id, :group_id, :name)";
				$stmt = $db->prepare($sql);

				echo 'open file';
				// read line by line in csv file
				while (($data_row = fgetcsv($f)) != FALSE) {

					// insert presenter info
					$data[':presenter_id'] = $data_row[0];
					$data[':name'] = $data_row[1];
					$data[':group_id'] = get_group_id_by_name($db, $data_row[2]);

					$stmt->execute($data);
				}
			}
			catch (Exception $e) {
				cleanup_db($db);
				echo json_encode(array('status' => 'fail', 'errorMsg' => $e->getMessage()));
				exit;
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

	// add time manually
	$time_cnt = $_POST['time-cnt'];

	try {
		$sql = "INSERT INTO `timeslots` (`date`, `start_time`, `end_time`, `time_order`, `occupied`) VALUES (:date, :start_time, :end_time, :time_order, 0)";
		$stmt = $db->prepare($sql);
	}
	catch (Exception $e) {
		cleanup_db($db);
		echo json_encode(array('status' => 'fail', 'errorMsg' => $e->getMessage()));
		exit;
	}

	for ($i = 0; $i < $time_cnt; ++$i) {

		if (!isset($_POST['quota-' . $i]) || $_POST['quota-' . $i] == 0) {
			continue;
		}

		$start_date = $_POST['start-date-' . $i] == '' ? 'NULL' : new DateTime($_POST['start-date-' . $i]);
		$end_date = $_POST['end-date-' . $i] == '' ? 'NULL' : new DateTime($_POST['end-date-' . $i]);
		$start_time = $_POST['start-time-' . $i] == '' ? 'NULL' : new DateTime($_POST['start-time-' . $i]);
		$end_time = $_POST['end-time-' . $i] == '' ? 'NULL' : new DateTime($_POST['end-time-' . $i]);

		$repeat_mode = $_POST['repeat-' . $i];
		$quota = $_POST['quota-' . $i];

		$interval = 'P1D';

		if ($repeat_mode == 'weekly') {
			$interval = 'P7D';
		}
		else if ($repeat_mode == 'monthly') {
			$interval = 'P1M';
		}
		else if ($repeat_mode == 'none') {
			$end_date = clone $start_date;
		}

		for ($date_i = clone $start_date; $date_i <= $end_date; $date_i->add(new DateInterval($interval))) {
			for ($j = 0; $j < $quota; ++$j) {
				try {
					$stmt->execute(array(':date' => $date_i->format('Y-m-d'), ':start_time' => $start_time->format('H:i'), ':end_time' => $end_time->format('H:i'), ':time_order' => ($j + 1)));
				}
				catch (Exception $e) {
					cleanup_db($db);
					echo json_encode(array('status' => 'fail', 'errorMsg' => $e->getMessage()));
					exit;
				}
			}
		}

	}

	// add presenter manually
	$presenter_cnt = $_POST['presenter-cnt'];

	try {
		$presenter_sql = "INSERT INTO `presenters` (`presenter_id`, `name`, `group_id`) VALUES (:presenter_id, :name, :group_id)";
		$stmt = $db->prepare($presenter_sql);
	}
	catch (Exception $e) {
		cleanup_db($db);
		echo json_encode(array('status' => 'fail', 'errorMsg' => $e->getMessage()));
		exit;
	}

	for ($i = 0; $i < $presenter_cnt; ++$i) {

		if (!isset($_POST['presenter-id-' . $i])) {
			continue;
		}

		try {
			$presenter_id = $_POST['presenter-id-' . $i];
			$name = $_POST['presenter-name-' . $i];
			$group_name = $_POST['presenter-group-' . $i];
			$group_id = get_group_id_by_name($group_name);
			$stmt->execute(array(':presenter_id' => $presenter_id, ':name' => $name, ':group_id' => $group_id));
		}
		catch (Exception $e) {
			cleanup_db($db);
			echo json_encode(array('status' => 'fail', 'errorMsg' => $e->getMessage()));
			exit;
		}
	}

	// mark setup process be ready to prevent setup again
	$sql = "INSERT INTO `attributes` (`attr`, `value`) VALUES (:attr, :value)";
	$stmt = $db->prepare($sql);
	$stmt->execute(array(':attr' => 'setup', ':value' => 'yes'));

	echo json_encode(array('status' => 'success', 'url' => $url));
}
else {
	echo 'invalid';
}



?>

