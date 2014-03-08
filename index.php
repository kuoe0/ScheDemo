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
	header("Location: register.php");
}

?>

<html>
	<head>
		<title>ScheDemo</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<link href="static/components/normalize-css/normalize.css" rel="stylesheet" />

		<script src="static/components/jquery/dist/jquery.min.js"></script>

		<link href="static/components/semantic/build/packaged/css/semantic.min.css" rel="stylesheet" />
		<script src="static/components/semantic/build/packaged/javascript/semantic.min.js"></script>


		<script src="static/js/main.js"></script>
		<link href="static/css/style.css" rel="stylesheet" />
	</head>
	<body>
		<div id="content" class="ui one column page grid">
			<div class="column">
				<h1 id="title" class="ui center aligned header">ScheDemo</h1>
				<h2 id="subtitle" class="ui center aligned header">An application for scheduling.</h2>
				<div class="centerize-box">
					<div id="btn-setup" class="ui green button">Start</div>
				</div>
			</div>
		</div>
		<div id="footer" class="ui one column page grid">
			<div class="column">
				<p>Powered by <a href="http://kuoe0.tw/">KuoE0</a>.</p>
			</div>
		</div>
	</body>
</html>

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

