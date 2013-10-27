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
		<title>Setup for PresentReg</title>
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<h1>Setup</h1>
				<form class="setup-form" action="setup-ing.php" method="POST" enctype="multipart/form-data">
					<legend>Basic Info</legend>
					<div class="form-group horizontal_input">
						<label>Site Name</label>
						<input class="form-control" type="text" name="title" placeholder="Type the title for this site..." />
						<span class="help-block">The title of this site, e.g. the name of course.</span>
					</div>
					<div class="form-group horizontal_input">
						<label>URL</label>
						<input class="form-control" type="url" name="url" value=<?php echo '"' . dirname(getURL()) . '"'; ?> />
						<span class="help-block">If you don't have some special reason, you don't need to modify this column.</span>
					</div>
					<legend>Admin Settings</legend>
					<div class="form-group horizontal_input">
						<label>Username</label>
						<input class="form-control" type="text" name="username" placeholder="username" />
					</div>
					<div class="form-group horizontal_input">
					<label>Password</label>
						<input class="form-control" type="password" name="password" placeholder="password" />
					</div>
					<legend>Data Importing</legend>
					<div class="form-group horizontal_input">
						<label>Presenter(s) List (CSV file)</label>
						<input class="form-control" type="file" name="presenter_list" />
						<span class="help-block">Upload file of presenters list.</span>
					</div>
					<div class="form-group horizontal_input">
						<label>Time Slot List (CSV file)</label>
						<input class="form-control" type="file" name="timeslot_list" />
						<span class="help-block">Import the time slots by file.</span>
					</div>
					<div class="form-group">
						<label>Time Slot Rule</label>&nbsp;&nbsp;
						<button class="btn btn-primary btn-xs" type="button" onclick="add_rule()" href="#">add a rule</button>
						<div id="timeslot_rules">
						</div>
						<span class="help-block">Setup time slots manually.<br />[Begin Date | End Date | Repeat Mode | Begin Time | End Time | Number of Presenters]</span>
					</div>
					<input type="hidden" name="rule-cnt" />
					<div class="form-group">
						<button class="btn btn-success btn-lg" type="submit" name="submit">Confirm</button>
					</div>
				</form>
			</div>
		</div>

		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script>
			var rule_cnt = 0;

			function add_rule() {

				datetime_rule = jQuery('<div class="datetime-rule" id="datetime-' + rule_cnt + '"><span class="glyphicon glyphicon-calendar"></span>&nbsp;&nbsp;<input type="date" name="begin-date-' + rule_cnt + '" /> ~ <input type="date" name="end-date-' + rule_cnt + '" disabled /><span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;<select name="repeat-' + rule_cnt + '" onchange="EndDateStatus(' + rule_cnt + ', this)" ><option value="none">none</option><option value="daily">daily</option><option value="weekly">weekly</option><option value="monthly">monthly</option></select><span class="glyphicon glyphicon-time"></span> <input type="time" name="begin-time-' + rule_cnt + '" /> ~ <input type="time" name="end-time-' + rule_cnt + '" /> <span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;<input type="number" name="quota-' + rule_cnt + '" min="1" value="1" />&nbsp;&nbsp;<span class="glyphicon glyphicon-remove" onclick="remove_rule(' + rule_cnt + ')"></span></div>');

				jQuery('#timeslot_rules').append(datetime_rule);
				++rule_cnt;

				$('input[name="rule-cnt"]').val(rule_cnt);
			};

			function remove_rule(id) {
				jQuery('#datetime-' + id).remove();
			}

			function EndDateStatus(id, sel) {
				if (sel.value == 'none') {
					$('input[name="end-date-' + id + '"]').attr('disabled', 'disabled');
				}
				else {
					$('input[name="end-date-' + id + '"]').removeAttr('disabled');
				}
			}

			
		</script>
	</body>
</html>

