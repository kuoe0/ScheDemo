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
	</head>
	<body>
		<div class="container">
			<h1><?php echo $title ?></h1>
			<div class="timeslot">
				<table class="table table-striped">
					<caption>Presentation Order</caption>
					<tr>
						<th>Time</th>
						<th>Order</th>
						<th>Presenter</th>
						<th>Title</th>
					</tr>
<?php
$sql = "SELECT * FROM `timeslots` ORDER BY `begin` ASC, `slice` ASC";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {
	echo '<tr>';
	echo '<td>' . $data_row['begin'] . " ~ " . $data_row['end'] . '</td>';
	echo '<td>' . $data_row['slice'] . "</td>";
	echo '</tr>';

}


?>
				</table>
			</div>
			<div class="group">
				<table class="table table-striped">
					<tr>
						<th>Group ID</th>
						<th>Members</th>
					</tr>
<?php
$sql = "SELECT * FROM `groups` ORDER BY `group_id` ASC";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {
	echo '<tr>';
	echo '<td>' . $data_row['group_id'] . '</td>';

	echo '<td>';
	$members_id = explode(',', $data_row['members']);

	for ($i = 0; $i < count($members_id); ++$i) {
		$sql = "SELECT name FROM `students` WHERE `student_id` = :student_id";
		$stmt2 = $db->prepare($sql);
		$stmt2->execute(array(':student_id' => $members_id[$i]));
		$name = $stmt2->fetch()['name'];
		echo $name . '<br />';
	}

	echo '</td>';
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
