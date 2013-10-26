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
include_once 'function.php';

try {

	$sql = "SELECT `value` FROM `attributes` WHERE `attr` = 'setup'";
	$stmt = $db->prepare($sql);
	$stmt->execute();
	$setup = $stmt->fetch()['value'];

	if ($setup == 'yes') {

		echo "<h1>It has already setuped. Direct after 5 sec...</h1>";

		$sql = "SELECT `value` FROM `attributes` WHERE `attr` = 'url'";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$url = $stmt->fetch()['value'];

		header("Refresh: 5; URL=" . $url);
		die;
	}

}
catch (PDOException $e) {
	echo $e->getMessage();
}

?>

<html>
	<head>
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="span4 offset4">
					<h1>Setup</h1>
					<form action="setup-ing.php" method="POST" enctype="multipart/form-data">
						<legend>Basic Info</legend>
						<label>Site Name</label>
						<input type="text" name="title" placeholder="Type the title for this site..." />
						<label>URL</label>
						<input type="url" name="url" value=<?php echo '"' . dirname(getURL()) . '"'; ?> />
						<span class="help-block">If you don't have some special reason, you don't need to modify this column.</span>
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
						<input type="hidden" name="submit" value="1" />
						<button class="btn btn-primary" type="submit">Confirm</button>
					</form>
				</div>
			</div>
		</div>
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>

