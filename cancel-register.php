<?php
/**
 * Short description for cancel-register.php
 *
 * Copyright (C) 2014 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 *
 * @package cancel-register
 * @author KuoE0 <kuoe0.tw@gmail.com>
 * @version 0.1
 * @copyright (C) 2014 KuoE0 <kuoe0.tw@gmail.com>
 */


include_once 'db_con.php';
include_once 'global.php';
include_once 'function.php';

$time_id = $_POST['time-id'];
$passwd = $_POST['password'];

$presentation_info = get_presentation_info_by_time_id($db, $time_id);
$presentation_id = $presentation_info['id'];
$group_id = $presentation_info['group_id'];

if (!password_check($db, $group_id, $passwd)) {
	echo json_encode(array('status' => false, 'msg' => 'Invalid password.'));
	die;
}

if (delete_presentation($db, $presentation_id)) {
	echo json_encode(array('status' => true));
}
else {
	echo json_encode(array('status' => false));
}

?>

