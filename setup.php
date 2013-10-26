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
				<div class="col-md-4 col-md-offset-4">
					<h1>Setup</h1>
					<form action="setup-ing.php" method="POST" enctype="multipart/form-data">
						<legend>Basic Info</legend>
						<div class="form-group">
							<label>Site Name</label>
							<input class="form-control" type="text" name="title" placeholder="Type the title for this site..." />
						</div>
						<div class="form-group">
							<label>URL</label>
							<input class="form-control" type="url" name="url" value=<?php echo '"' . dirname(getURL()) . '"'; ?> />
							<span class="help-block">If you don't have some special reason, you don't need to modify this column.</span>
						</div>
						<legend>Admin Settings</legend>
						<div class="form-group">
							<label>Username</label>
							<input class="form-control" type="text" name="username" placeholder="username" />
						</div>
						<div class="form-group">
						<label>Password</label>
							<input class="form-control" type="password" name="password" placeholder="password" />
							<legend>Data Upload</legend>
						<div class="form-group">
							<label>Student List (CSV file)</label>
							<input class="form-control" type="file" name="student_list" />
						</div>
						<div class="form-group">
							<label>Group List (CSV file)</label>
							<input class="form-control" type="file" name="group_list" />
						</div>
						<div class="form-group">
							<label>Time Slot List (CSV file)</label>
							<input class="form-control" type="file" name="timeslot_list" />
						</div>
						<input type="hidden" name="submit" value="1" />
						<div class="form-group">
							<button class="btn btn-primary" type="submit">Confirm</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>

