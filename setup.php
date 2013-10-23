<?php
/**
 * Short description for setup.php
 *
 * Copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 *
 * @package setup
 * @author KuoE0 <kuoe0.tw@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 */

include_once 'db_con.php';

try {

	$stmt = $db->prepare("SELECT `value` FROM `attributes` WHERE `attr` = 'status'");
	$stmt->execute();
	$data_row = $stmt->fetch();

}
catch (PDOException $e) {
	echo $e->getMessage();
}

?>

<html>
	<head>
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="span4 offset4">
					<h1>Setup</h1>
					<form method="POST" enctype="multipart/form-data">
						<legend>Title</legend>
						<input type="text" name="title" placeholder="Type the title for this site..." />
						<legend>Admin Settings</legend>
						<label>Username</label>
						<input type="text" name="username" placeholder="username" />
						<label>Password</label>
						<input type="password" name="password" placeholder="password" />
						<legend>Data Upload</legend>
						<label>Student List (CSV file)</label>
						<input type="file" name="student_list" />
						<label>Group List (CSV file)</label>
						<input type="file" name="group_list" />
						<label>Time Slot List (CSV file)</label>
						<input type="file" name="timeslot_list" />
						<button class="btn btn-primary" type="submit">Confirm</button>
					</form>
				</div>
			</div>
		</div>
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>

