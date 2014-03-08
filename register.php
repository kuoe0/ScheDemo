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
		<div id="content" class="ui one column page grid">
			<div class="column">
				<h1 id="title" class="ui center aligned header"><?php echo $title;?></h1>
				<table id="timetable" class="ui fourteen column large padded table segment">
					<thead>
						<tr>
							<th class="two wide">Date</th>
							<th class="two wide">Time</th>
							<th class="one wide">Order</th>
							<th class="four wide">Presenter</th>
							<th class="five wide">Title</th>
							<th class="one wide">Register</th>
						</tr>
					</thead>
					<tbody>
<?php

$sql = "SELECT * FROM `timeslots`";
$stmt = $db->prepare($sql);
$stmt->execute();

$html_template = "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><i class='add icon'></i></td></tr>";

while (($data_row = $stmt->fetch()) != false) {

	$time_id = $data_row['time_id'];
	$date = $data_row['date'];
	$start_time = $data_row['start_time'];
	$end_time = $data_row['end_time'];
	$time_order = $data_row['time_order'];
	$occupied = $data_row['occupied'];

	echo sprintf($html_template, $date, $start_time . ' ~ ' . $end_time, $time_order, '', '');
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

