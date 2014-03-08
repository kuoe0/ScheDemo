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
include_once 'global.php';
include_once 'function.php';

$sql = "SELECT `value` FROM `attributes` WHERE `attr` = 'setup'";
$stmt = $db->prepare($sql);
$stmt->execute();
$nodata = !$stmt->fetch();

if (!$nodata) {
	header("Location: register.php");
}

?>

<html>
	<head>
		<title>ScheDemo</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

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
				<h1 id="title" class="ui center aligned header">ScheDemo</h1>
				<h2 id="subtitle" class="ui center aligned header">An application for scheduling.</h2>
				<div class="centerize-box">
					<div id="btn-setup" class="ui green button">Start</div>
				</div>
			</div>
		</div>
		<div id="footer" class="ui one column page grid">
			<div class="column">
				<p>Powered by <a href="http://kuoe0.tw/">KuoE0</a>.</p>
			</div>
		</div>
	</body>
</html>

