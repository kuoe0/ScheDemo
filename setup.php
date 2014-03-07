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

				<form name="setup" class="ui form" action="setup-ing.php" method="POST" enctype="multipart/form-data">
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
								<input type="date" name="start-opening-date" />
								<i class="calendar icon"></i>
							</div>
						</div>
						<div class="parallel field">
							<div class="ui left icon input">
								<input type="time" name="start-opening-time" />
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
								<input type="file" name="presenter-list" />
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
								<input type="file" name="time-list" />
							</div>
						</div>
					</div>

					<div>
						<div id="btn-add-time" class="ui mini right floated blue button">
							<i class="add icon"></i>
							Time
						</div>
						<legend>Manual Time Importing</legend>
						<div id="time-list" class="ui two column grid">
						</div>
					</div>

					<divid="presenter-list">
						<div id="btn-add-presenter" class="ui mini right floated blue button">
							<i class="add icon"></i>
							Presenter
						</div>
						<legend>Manual Presenter(s) Importing</legend>
						<div id="presenter-list" class="ui two column grid">
						</div>
					</div>

					<input type="hidden" name="time-cnt" value="0" />
					<input type="hidden" name="presenter-cnt" value="0" />
					<div class="ui divider"></div>

					<input class="ui green button" type="submit" name="submit" value="Confirm" />
				</form>
			</div>
		</div>
		<div id="footer" class="ui one column page grid">
			<div class="column">
				<p>Powered by <a href="http://kuoe0.tw/">KuoE0</a>.</p>
			</div>
		</div>
	</body>
</html>

