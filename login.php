<?php
/**
 * Short description for login.php
 *
 * Copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 *
 * @package login
 * @author KuoE0 <kuoe0.tw@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 */

include_once 'db_con.php';
include_once 'global.php';
include_once 'function.php';


?>

<html>
	<head>
		<title>Login</title>
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<form class="form-signin">
				<legend>Please sign in...</legend>
				<div class="form-group">
					<label>ID</label>
					<select class="col-lg-12 input-lg" name="ID">
						<option value=0>Admin</option>
					</select>
					<label>Password</label>
					<input class="col-lg-12 input-lg" type="password" name="password" placeholder="Password..."/>
					<label>
						<input type="checkbox" name="rememberme" />
					Remember me
					</label>
					<button class="btn btn-success btn-lg btn-block" type="submit" name="submit">Sign in</button>
				</div>
			</form>
		</div>
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>



