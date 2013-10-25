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
include_once 'function.php';

$sql = "SELECT `value` FROM `attributes` WHERE `attr` = 'title'";
$stmt = $db->prepare($sql);
$stmt->execute();

$title = $stmt->fetch()['value'];

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
			<h1><?php echo $title ?></h1>
			<div class="register">
				<form action="register.php" method="POST">
					<div class="horizontal_input">
						<label><i class="icon-user"></i> Group</label>
						<select name="group_id">
<?php
$sql = "SELECT `group_id`, `registered` FROM `groups`";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {
	if ($data_row['registered'] == '0') {
		$id = $data_row['group_id'];
		$name_list = get_member_names($db, $id);
		echo '<option value=' . $id . '>' . $id . '. ' . implode('；', $name_list) . '</option>';
	}
}

?>
						</select>
					</div>

					<div class="horizontal_input">
						<label><i class="icon-time"></i> Time</label>
						<select name="time_id">
<?php
$sql = "SELECT * FROM `timeslots` WHERE `occupied` = '0'";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {
	if ($data_row['occupied'] == '0') {
		$id = $data_row['time_id'];
		$time = $data_row['begin'];
		$order = $data_row['slice'];
		echo '<option value=' . $id . '>' . $time . ' - No. ' . $order . '</option>';
	}
}

?>
						</select>
					</div>

					<div class="horizontal_input">
						<label><i class="icon-bookmark"></i> Presentation Title</label>
						<input type="text" name="title" />
					</div>

					<div class="horizontal_input">
						<button class="btn btn-submit" type="submit">Confirm</button>
					</div>


				</form>
			</div>

			<div class="timeslot">
				<table class="table table-hover">
					<tr>
						<th class="time_col">Time</th>
						<th class="order_col">Order</th>
						<th class="group_col">Presenter</th>
						<th class="title_col">Title</th>
					</tr>
<?php
$sql = "SELECT * FROM `timeslots` ORDER BY `begin` ASC, `slice` ASC";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {
	$time_id = $data_row['time_id'];
	$presentation_info = get_presentation_info_by_time_id($db, $time_id);
	$name_list = get_member_names($db, $presentation_info['group_id']);

	if ($data_row['occupied'] == '1') {
		echo '<tr class="warning">';
	}
	else {
		echo '<tr>';
	}
	
	if ($data_row['end'] != '') {
		echo '<td class="time_col">' . $data_row['begin'] . " ~ " . $data_row['end'] . '</td>';
	}
	else {
		echo '<td class="time_col">' . $data_row['begin'] . '</td>';
	}
	echo '<td class="order_col">No. ' . $data_row['slice'] . "</td>";
	if ($data_row['occupied'] == '1') {
		echo '<td class="group_col"><span class="group_id">Group ' . $presentation_info['group_id']. '</span><br />' . implode('<br />', $name_list) . '</td>';
		echo '<td class="title_col">' . $presentation_info['title'] . '</td>';
	}
	else {
		echo '<td class="group_col"></td><td class="title_col"></td>';
	}
	echo '</tr>';

}


?>
				</table>
			</div>

		</div>
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
