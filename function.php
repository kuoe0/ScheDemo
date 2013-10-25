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

function cleanup_db($db) {
	$db->exec("DELETE FROM `attributes`");
	$db->exec("DELETE FROM `students`");
	$db->exec("DELETE FROM `groups`");
	$db->exec("DELETE FROM `timeslots`");
	$db->exec("DELETE FROM `presentations`");
}

function get_member_names($db, $id) {
	$sql = "SELECT `members` FROM `groups` WHERE `group_id` = :group_id";
	$stmt = $db->prepare($sql);
	$stmt->execute(array(':group_id' => $id));

	$members_id = explode(',', $stmt->fetch()['members']);
	sort($members_id);

	$ret = array();
	for ($i = 0; $i < count($members_id); ++$i) {
		$sql = "SELECT `name` FROM `students` WHERE `student_id` = :student_id";
		$stmt = $db->prepare($sql);
		$stmt->execute(array(':student_id' => $members_id[$i]));

		array_push($ret, $stmt->fetch()['name']);
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

