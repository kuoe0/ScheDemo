<?php
/**
 * Short description for function.php
 *
 * Copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 *
 * @package function
 * @author KuoE0 <kuoe0.tw@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 */

function getURL() {
	$protocol = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://';
	$port = $_SERVER['SERVER_PORT'] == '80' ? '' : $_SERVER['SERVER_PORT'];
	return $protocol . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function cleanup_db($db) {
	$db->exec("DELETE FROM `attributes`");
	$db->exec("DELETE FROM `presenters`");
	$db->exec("DELETE FROM `timeslots`");
	$db->exec("DELETE FROM `presentations`");
}

function get_member_names($db, $group_id) {
	$sql = "SELECT `name` FROM `presenters` WHERE `group_id` = :group_id ORDER BY `presenter_id` ASC";
	$stmt = $db->prepare($sql);
	$stmt->execute(array(':group_id' => $group_id));

	$ret = array();

	while (($data_row = $stmt->fetch()) != FALSE) {
		array_push($ret, $data_row['name']);
	}
	return $ret;
}

function get_presentation_info_by_time_id($db, $time_id) {
	$sql = "SELECT * FROM `presentations` WHERE `time_id` = :time_id";
	$stmt = $db->prepare($sql);
	$stmt->execute(array(':time_id' => $time_id));

	return $stmt->fetch();
}

	
?>

