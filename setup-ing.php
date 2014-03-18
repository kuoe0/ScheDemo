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

$status = true;

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
		echo $e->getMessage();
		$status = false;
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

		if ($start_opening != '') {
			$stmt->execute(array(':attr' => 'start-opening', ':value' => $start_opening));
		}

		if ($end_opening != '') {
			$stmt->execute(array(':attr' => 'end-opening', ':value' => $end_opening));
		}
	}
	catch (Exception $e) {
		echo $e->getMessage();
		$status = false;
	}

	// parse timeslots list
	if ($_FILES['time-list']['error'] == 0) {

		$filename = $_FILES['time-list']['name'];
		$type = $_FILES['time-list']['type'];
		$tmp_name = $_FILES['time-list']['tmp_name'];

		if (($f = fopen($tmp_name, 'r')) != FALSE) {
			// for the large csv file
			set_time_limit(0);
			// read line by line in csv file
			while (($data_row = fgetcsv($f)) != FALSE) {
				// insert group info
				$rule = array();
				$rule['start_date'] = new DateTime($data_row[0]);
				$rule['end_date'] = new DateTime($data_row[1]);
				$rule['start_time'] = new DateTime($data_row[2]);
				$rule['end_time'] = new DateTime($data_row[3]);
				$rule['repeat_mode'] = trim($data_row[4]);
				$rule['quota'] = trim($data_row[5]);

				if (!add_timeslot_by_rule($db, $rule)) {
					$status = false;
				}
			}
		}
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

				// read line by line in csv file
				while (($data_row = fgetcsv($f)) != FALSE) {

					// insert presenter info
					$data[':presenter_id'] = trim($data_row[0]);
					$data[':name'] = trim($data_row[1]);
					$data[':group_id'] = get_group_id_by_name($db, trim($data_row[2]));

					$stmt->execute($data);
				}
			}
			catch (Exception $e) {
				echo $e->getMessage();
				$status = false;
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

		$rule = array();
		$rule['start_date'] = $_POST['start-date-' . $i] == '' ? 'NULL' : new DateTime($_POST['start-date-' . $i]);
		$rule['end_date'] = $_POST['end-date-' . $i] == '' ? 'NULL' : new DateTime($_POST['end-date-' . $i]);
		$rule['start_time'] = $_POST['start-time-' . $i] == '' ? 'NULL' : new DateTime($_POST['start-time-' . $i]);
		$rule['end_time'] = $_POST['end-time-' . $i] == '' ? 'NULL' : new DateTime($_POST['end-time-' . $i]);

		$rule['repeat_mode'] = $_POST['repeat-' . $i];
		$rule['quota'] = $_POST['quota-' . $i];

		if (!add_timeslot_by_rule($db, $rule)) {
			$status = false;
		}
	}

	// add presenter manually
	$presenter_cnt = $_POST['presenter-cnt'];

	try {
		$presenter_sql = "INSERT INTO `presenters` (`presenter_id`, `name`, `group_id`) VALUES (:presenter_id, :name, :group_id)";
		$stmt = $db->prepare($presenter_sql);
	}
	catch (Exception $e) {
		echo $e->getMessage();
		$status = false;
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
			echo $e->getMessage();
			$status = false;
		}
	}

	try {
		// mark setup process be ready to prevent setup again
		$sql = "INSERT INTO `attributes` (`attr`, `value`) VALUES (:attr, :value)";
		$stmt = $db->prepare($sql);
		$stmt->execute(array(':attr' => 'setup', ':value' => 'yes'));
	}
	catch (Exception $e) {
		echo $e->getMessage();
		$status = false;
	}
}
else {
	$status = false;
}

if (!$status) {
	cleanup_db($db);
}

?>

<html>
	<head>
		<title>Setup Result</title>
		<link href="static/components/normalize-css/normalize.css" rel="stylesheet" />

		<script src="static/components/jquery/dist/jquery.min.js"></script>

		<link href="static/components/semantic/build/packaged/css/semantic.min.css" rel="stylesheet" />
		<script src="static/components/semantic/build/packaged/javascript/semantic.min.js"></script>

		<script src="static/js/main.js"></script>
		<link href="static/css/style.css" rel="stylesheet" />
	</head>
	<body>
		<div class="ui three column grid">
			<div class="column">
			</div>
			<div class="column vertical-centering">
				<div class="ui <?php echo $status ? 'green' : 'red';?> segment">
					<h1 id="title" class="ui center aligned header"><?php echo $status ? 'Successed!' : 'Failed!';?></h1>
					<input type="hidden" name="index" value="<?php echo $url;?>" />
					<div class="centerize-box">
						<div id="btn-index" class="ui <?php echo $status ? 'green' : 'red';?> button">
							Go Back
						</div>
					</div>
				</div>
			</div>
			<div class="column">
			</div>
		</div>
	</body>
</html>

