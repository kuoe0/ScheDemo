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
include_once 'global.php';
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
				<h1 id="title" class="ui center aligned header">Setup</h1>

				<form class="ui form" action="setup-ing.php" method="POST" enctype="multipart/form-data">
					<legend>Basic Info</legend>
					<div class="two fields">
						<div class="field">
							<label>Site Name</label>
							<div class="ui left icon input">
								<input type="text" name="title" placeholder="Type the title for this site..." />
								<i class="bookmark icon"></i>
								<span class="help-block">The title of this site, e.g. the name of course.</span>
							</div>
						</div>

						<div class="field">
							<label>URL</label>
							<div class="ui left icon input">
								<input type="url" name="url" value=<?php echo '"' . dirname(getURL()) . '"'; ?> />
								<i class="url icon"></i>
								<span class="help-block">If you don't have some special reason, you don't need to modify this column.</span>
							</div>
						</div>
					</div>
					<legend>Opening Time</legend>
					<div class="four fields">
						<div class="field">
							<label>From</label>
							<div class="ui left icon input">
								<input type="date" name="begin-opening-date" />
								<i class="calendar icon"></i>
							</div>
						</div>
						<div class="parallel field">
							<div class="ui left icon input">
								<input type="time" name="begin-opening-time" />
								<i class="time icon"></i>
							</div>
						</div>
						<div class="field">
							<label>To</label>
							<div class="ui left icon input">
								<input type="date" name="end-opening-date" />
								<i class="calendar icon"></i>
							</div>
						</div>
						<div class="parallel field">
							<div class="ui left icon input">
								<input type="time" name="end-opening-time" />
								<i class="time icon"></i>
							</div>
						</div>
					</div>

					<legend>Administrator</legend>
					<div class="two fields">
						<div class="field">
							<label>Username</label>
							<div class="ui left icon input">
								<input type="text" name="username" placeholder="username" />
								<i class="user icon"></i>
							</div>
						</div>
						<div class="field">
							<label>Password</label>
							<div class="ui left icon input">
								<input type="password" name="password" placeholder="password" />
								<i class="key icon"></i>
							</div>
						</div>
					</div>

					<legend>File Importing</legend>
					<div class="four fields">
						<div class="field">
							<label>Presenter(s)</label>
							<div class="ui icon input">
								<input id="presenter-list-filename" type="text" placeholder="filename" disabled/>
								<i class="delete basic icon"></i>
							</div>
							<span class="help-block">Upload file of presenters list.</span>
						</div>
						<div class="parallel field">
							<div class="ui upload blue button">
								<i class="upload basic icon"></i>
								upload
								<input id="btn-presenter-list" type="file" name="presenter-list" />
							</div>
						</div>

						<div class="field">
							<label>Time</label>
							<div class="ui icon input">
								<input id="time-list-filename" type="text" placeholder="filename" disabled/>
								<i class="delete basic icon"></i>
							</div>
							<span class="help-block">Upload file of time list.</span>
						</div>
						<div class="parallel field">
							<div class="ui upload blue button">
								<i class="upload basic icon"></i>
								upload
								<input id="btn-time-list" type="file" name="time-list" />
							</div>
						</div>
					</div>

					<div id="time-rule-list">
						<div class="ui mini right floated blue button">
							<i class="add icon"></i>
							Add
						</div>
						<legend>Manual Time Importing</legend>
						<div class="ui two column grid">
							<div class="column">
								<div class="ui raised segment">
									<div class="ui right red corner label">
										<i class="delete basic icon"></i>
									</div>
									<div class="two fields">
										<div class="field">
											<label>Start Date</label>
											<div class="ui left icon input">
												<input type="date" name="start-date" />
												<i class="calendar icon"></i>
											</div>
										</div>
										<div class="field">
											<label>End Date</label>
											<div class="ui left icon input">
												<input type="date" name="end-date" />
												<i class="calendar icon"></i>
											</div>
										</div>
									</div>

									<div class="two fields">
										<div class="field">
											<label>Start Time</label>
											<div class="ui left icon input">
												<input type="time" name="start-time" />
												<i class="time icon"></i>
											</div>
										</div>
										<div class="field">
											<label>End Time</label>
											<div class="ui left icon input">
												<input type="time" name="end-time" />
												<i class="time icon"></i>
											</div>
										</div>
									</div>

									<div class="two fields">
										<div class="field">
											<label># of People</label>
											<div class="ui left icon input">
												<input type="number" name="quota" />
												<i class="users icon"></i>
											</div>
										</div>
										<div class="field">
											<label>Repeat</label>
											<select name="repeat-mode">
												<option value="none">None</option>
												<option value="daily">Daily</option>
												<option value="weekly">Weekly</option>
												<option value="monthly">Monthly</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div id="presenter-list">
						<div class="ui mini right floated blue button">
							<i class="add icon"></i>
							Add
						</div>
						<legend>Manual Presenter(s) Importing</legend>
						<div class="ui two column grid">
							<div class="column">
								<div class="ui raised segment">
									<div class="ui right red corner label">
										<i class="delete basic icon"></i>
									</div>
									<div class="three fields">
										<div class="field">
											<label>ID</label>
											<input type="text" name="presenter-id" />
										</div>
										<div class="field">
											<label>Name</label>
											<input type="text" name="presenter-name" />
										</div>
										<div class="field">
											<label>Group</label>
											<input type="text" name="presenter-group" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<input type="hidden" name="time-rule-cnt" value=0/>
					<input type="hidden" name="presenter-cnt" value=0/>

					<div class="ui divider"></div>

					<div class="ui green button">
						Confirm
					</div>
				</form>
			</div>
		</div>
		<div id="footer" class="ui one column page grid">
			<div class="column">
				<p>Powered by <a href="http://kuoe0.tw/">KuoE0</a>.</p>
			</div>
		</div>

		<script>
			var rule_cnt = 0;

			function add_rule() {

				datetime_rule = jQuery('<div class="datetime-rule" id="datetime-' + rule_cnt + '"><span class="glyphicon glyphicon-calendar"></span>&nbsp;&nbsp;<input type="date" name="begin-date-' + rule_cnt + '" /> ~ <input type="date" name="end-date-' + rule_cnt + '" disabled /><span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;<select name="repeat-' + rule_cnt + '" onchange="EndDateStatus(' + rule_cnt + ', this)" ><option value="none">none</option><option value="daily">daily</option><option value="weekly">weekly</option><option value="monthly">monthly</option></select><span class="glyphicon glyphicon-time"></span> <input type="time" name="begin-time-' + rule_cnt + '" /> ~ <input type="time" name="end-time-' + rule_cnt + '" /> <span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;<input type="number" name="quota-' + rule_cnt + '" min="1" value="1" />&nbsp;&nbsp;<span class="glyphicon glyphicon-trash" onclick="remove_rule(' + rule_cnt + ')"></span></div>');

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

