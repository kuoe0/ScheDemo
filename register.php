<?php
/**
 * Short description for register.php
 *
 * Copyright (C) 2014 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 *
 * @package register
 * @author KuoE0 <kuoe0.tw@gmail.com>
 * @version 0.1
 * @copyright (C) 2014 KuoE0 <kuoe0.tw@gmail.com>
 */

include_once 'db_con.php';
include_once 'global.php';
include_once 'function.php';

$title = get_title($db);

?>
<html>
	<head>
		<title><?php echo $title;?> - ScheDemo</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<link href="static/components/normalize-css/normalize.css" rel="stylesheet" />

		<script src="static/components/jquery/dist/jquery.min.js"></script>

		<link href="static/components/semantic/build/packaged/css/semantic.min.css" rel="stylesheet" />
		<script src="static/components/semantic/build/packaged/javascript/semantic.min.js"></script>

		<link href="static/components/semantic/build/minified/modules/sidebar.min.css" rel="stylesheet" />
		<script src="static/components/semantic/build/minified/modules/sidebar.min.js"></script>

		<script src="static/js/main.js"></script>
		<link href="static/css/style.css" rel="stylesheet" />
	</head>
	<body>
		<div id="register-form" class="ui thin sidebar">
			<div class="ui basic segment">
				<form class="ui form" action="register-ing.php">
					<input type="hidden" name="time-id" />
					<div class="field">
						<label>Date</label>
						<input type="text" name="date" readonly />
					</div>
					<div class="field">
						<label>Time</label>
						<input type="text" name="time" readonly />
					</div>
					<div class="field">
						<label>Order</label>
						<input type="text" name="order" readonly />
					</div>
					<div class="field">
						<label>Presenter</label>
						<div class="ui fluid selection dropdown">
							<input type="hidden" name="group-id"/>
							<div class="text">Choose</div>
							<i class="dropdown icon"></i>
							<div class="menu">
<?php
$select_group_sql = "SELECT * FROM `groups` WHERE `registered` = 0";
$stmt = $db->prepare($select_group_sql);
$stmt->execute();

$html_template = '<div class="item" data-value="%d">%s</div>';

while (($data_row = $stmt->fetch()) != false) {
	$group_id = $data_row['group_id'];
	$group_name = $data_row['group_name'];

	echo sprintf($html_template, $group_id, $group_name);

}

?>
							</div>
						</div>
					</div>
					<div class="field">
						<label>Title</label>
						<input type="text" name="title" />
					</div>
					<div id="btn-register" class="ui mini button">
						Register
					</div>
					
				</form>
			</div>
		</div>
		<div id="content" class="ui one column page grid">
			<div class="column">
				<h1 id="title" class="ui center aligned header"><?php echo $title;?></h1>
				<table id="timetable" class="ui eighteen column large padded table segment">
					<thead>
						<tr>
							<th class="three wide">Date</th>
							<th class="three wide">Time</th>
							<th class="one wide">Order</th>
							<th class="four wide">Presenter</th>
							<th class="six wide">Title</th>
							<th class="one wide">Register</th>
						</tr>
					</thead>
					<tbody>
<?php

$sql = "SELECT * FROM `timeslots`";
$stmt = $db->prepare($sql);
$stmt->execute();

$html_template = "<tr id='timeslot-%d'><td id='date-%d'>%s</td><td id='time-%d'>%s</td><td id='order-%d'>%s</td><td id='presenter-%d'>%s</td><td id='title-%d'>%s</td><td><div id='btn-register-%d' class='circular ui mini icon basic button'><i class='pin basic icon'></i></div></td></tr>";

while (($data_row = $stmt->fetch()) != false) {

	$time_id = $data_row['time_id'];
	$date = $data_row['date'];
	$start_time = $data_row['start_time'];
	$end_time = $data_row['end_time'];
	$time_order = $data_row['time_order'];
	$occupied = $data_row['occupied'];

	$presentation_info = get_presentation_info_by_time_id($db, $time_id);
	$title = $presentation_info['title'];
	$group_id = $presentation_info['group_id'];
	$member_list = get_members_by_group_id($db, $presentation_info['group_id']);

	echo sprintf($html_template, $time_id, $time_id, $date, $time_id, $start_time . ' ~ ' . $end_time, $time_id, $time_order, $time_id, implode('<br/>', $member_list), $time_id, $title, $time_id);
}

?>
					</tbody>
				</table>
			</div>
		</div>
		<div id="footer" class="ui one column page grid">
			<div class="column">
				<p>Powered by <a href="http://github.com/KuoE0/ScheDemo/">ScheDemo</a>.</p>
			</div>
		</div>
	</body>
</html>

