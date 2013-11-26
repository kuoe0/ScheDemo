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
				<div class="form-group">
					<label id="ID">ID</label>
					<br />
					<label>
						<input type="checkbox" name="loginasadmin" onchange="login_as_admin(this)"/>
					Login as administrator
					</label>
					<select class="col-lg-12 input-lg" name="ID">
<?php
$sql = "SELECT DISTINCT `group_id`, `registered` FROM `presenters` ORDER BY `group_id` ASC";
$stmt = $db->prepare($sql);
$stmt->execute();

while (($data_row = $stmt->fetch()) != FALSE) {
	$group_id = $data_row['group_id'];
	$name_list = get_member_names($db, $group_id);
	echo '<option value=' . $group_id . '>' . $group_id . '. ' . implode('；', $name_list) . '</option>';
}

?>
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
		<script>

			function login_as_admin(chbox) {
				if (chbox.checked) {
					$('select[name="ID"]').attr('disabled', 'disabled');
					$('<input class="col-lg-12 input-lg" type="text" name="adminid" placeholder="Admin ID" />').insertAfter('#ID');
				}
				else {
					$('select[name="ID"]').removeAttr('disabled');
					$('input[name="adminid"]').remove();
				}
			}
		</script>
	</body>
</html>



