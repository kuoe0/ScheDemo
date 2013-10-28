<?php
/**
 * Short description for index.php
 *
 * Copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 *
 * @package index
 * @author KuoE0 <kuoe0.tw@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 */

include_once 'db_con.php';
include_once 'global.php';
include_once 'function.php';

$sql = "SELECT `value` FROM `attributes` WHERE `attr` = 'setup'";
$stmt = $db->prepare($sql);
$stmt->execute();
$nodata = !$stmt->fetch();

if (!$nodata) {
	$sql = "SELECT `value` FROM `attributes` WHERE `attr` = 'title'";
	$stmt = $db->prepare($sql);
	$stmt->execute();

	$title = $stmt->fetch()['value'];
}
else {
	$title = 'PresentReg';
}

?>

<html>
	<head>
		<title><?php echo $title ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="jumbotron">
				<h1><?php echo $title ?></h1>
<?php

if ($nodata) {
	echo '<p>PresentReg is a service of scheduling. It can be used to arrange the order of presentations or demos.';
	echo '<p><a class="btn btn-lg btn-success" href="setup.php">Setup & Use »</a></p>';
	echo '</div>';
	echo '<!--';
}

?>
			</div>
			<div class="register">
				<form action="register.php" method="POST">
					<div class="form-group horizontal_input">
						<label><i class="glyphicon glyphicon-user"></i> Presenter / Group</label>
						<select class="form-control" name="group_id">
<?php
$sql = "SELECT DISTINCT `group_id`, `registered` FROM `presenters` ORDER BY `group_id` ASC";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {
	$group_id = $data_row['group_id'];
	$name_list = get_member_names($db, $group_id);
	echo '<option ' . ($data_row['registered'] == '0' ? '' : 'disabled') . ' value=' . $group_id . '>' . $group_id . '. ' . implode('；', $name_list) . '</option>';
}

?>
						</select>
					</div>

					<div class="form-group horizontal_input">
						<label><i class="glyphicon glyphicon-time"></i> Time</label>
						<select class="form-control" name="time_id">
<?php
$sql = "SELECT * FROM (SELECT * FROM `timeslots` ORDER BY date(`date`)), (SELECT `time_id` AS `time_id_slice`, `slice` FROM `timeslots` ORDER BY `slice`) WHERE `time_id` == `time_id_slice`";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {
	$id = $data_row['time_id'];
	$date = $data_row['date'];
	$time = '';
	if ($data_row['begin_time'] != 'NULL') {
		$time .= $data_row['begin_time'];
	}
	if ($data_row['end_time'] != 'NULL') {
		$time .= ' ~ ' . $data_row['end_time'];
	}
	$order = $data_row['slice'];
	$format = $date . ($time == '' ? '' : (' ' . $time)) . ' - No. ' . $order;
	echo '<option ' . ($data_row['occupied'] == '0' ? '' : 'disabled') . ' value=' . $id . '>' . $format . '</option>';
}

?>
						</select>
					</div>

					<div class="form-group horizontal_input">
						<label><i class="glyphicon glyphicon-bookmark"></i> Presentation Title</label>
						<input class="form-control" type="text" name="title" />
					</div>

					<button class="btn btn-success" type="submit">Confirm</button>


				</form>
			</div>

			<div class="timeslot">
				<table class="table table-hover">
					<tr>
						<th class="date_col">Date</th>
						<th class="time_col">Time</th>
						<th class="order_col">Order</th>
						<th class="group_col">Presenter</th>
						<th class="title_col">Title</th>
					</tr>
<?php
$sql = "SELECT * FROM `timeslots` ORDER BY `date`, `begin_time`, `slice`";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {

	$time_id = $data_row['time_id'];
	$date = $data_row['date'];
	$begin_time = $data_row['begin_time'];
	$end_time = $data_row['end_time'];
	$order = $data_row['slice'];
	$occupied = $data_row['occupied'];

	$presentation_info = get_presentation_info_by_time_id($db, $time_id);
	$name_list = get_member_names($db, $presentation_info['group_id']);

	if ($occupied == '1') {
		echo '<tr class="success">';
	}
	else {
		echo '<tr>';
	}

	echo '<td class="date_col">' . $date . '</td>';
	echo '<td class="time_col">' . $begin_time . ($end_time != "NULL" ? (' ~ ' . $end_time) : '') . '</td>';
	echo '<td class="order_col">' . $order . '</td>';
	echo '<td class="group_col">' . ($occupied == '1' ? implode('<br />', $name_list) : '') . '</td>';
	echo '<td class="title_col">' . ($occupied == '1' ? $presentation_info['title'] : '') . '</td>';

	echo '</tr>';
}


?>
				</table>
			</div>

		</div>
<?php
if ($nodata) {
	echo '-->';
}
?>

		<div id="footer" class="container">
			<p class="muted">Powered by <a href="http://kuoe0.tw/">KuoE0</a>.</p>
		</div>
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
