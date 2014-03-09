<?php
/**
 * Short description for register.php
 *
 * Copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 *
 * @package register
 * @author KuoE0 <kuoe0.tw@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 */

include_once 'db_con.php';
include_once 'global.php';
include_once 'function.php';

$group_id = $_POST['group-id'];
$time_id = $_POST['time-id'];
$title = $_POST['title'];

$current_time = new DateTime(date('Y-m-d H:i', time()));
$start_opening = get_attr($db, 'start_opening');
if ($start_opening == '') {
	$start_opening = new DateTime(date('Y-m-d H:i', time()));
}
$end_opening = get_attr($db, 'end_opening');
if ($end_opening == '') {
	$end_opening = new DateTime(date('Y-m-d H:i', time()));
}

if ($current_time < $begin_opening) {
	echo json_encode(array('status' => false, 'msg' => 'System will start at ' . $start_opening . '.'));
	die;
}

if ($current_time > $end_opening) {
	echo json_encode(array('status' => false, 'msg' => 'System has been closed at ' . $end_opening . '.'));
	die;
}

$sql = "SELECT DISTINCT `occupied` FROM `timeslots` WHERE `time_id` = :time_id";
$stmt = $db->prepare($sql);
$stmt->execute(array(':time_id' => $time_id));
$data_row = $stmt->fetch();

if (!$data_row) {
	echo json_encode(array('status' => false, 'msg' => 'Invalid time.'));
	die;
}

if ($data_row['occupied'] == '1') {
	echo json_encode(array('status' => false, 'msg' => 'This time has been chosen!'));
	die;
}

$sql = "SELECT DISTINCT `registered` FROM `groups` WHERE `group_id` = :group_id";
$stmt = $db->prepare($sql);
$stmt->execute(array(':group_id' => $group_id));
$data_row = $stmt->fetch();

if (!$data_row) {
	echo json_encode(array('status' => false, 'msg' => 'Invalid presenter.'));
	die;
}

if ($data_row['registered'] == '1') {
	echo json_encode(array('status' => false, 'msg' => 'This presenter has been registered!'));
	die;
}

# generate random password
$passwd = gen_random_password();

if (add_presentation($db, $title, $time_id, $group_id, $passwd)) {
	echo json_encode(array('status' => true, 'msg' => 'Please remeber this password: ' . $passwd));
	die;
}

echo json_encode(array('status' => false));

?>

