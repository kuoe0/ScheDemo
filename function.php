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

function is_setup($db) {
	if (get_attr($db, 'setup') == '') {
		return false;
	}
	return true;
}

function cleanup_db($db) {
	try {
		$db->exec("DELETE FROM `attributes`");
		$db->exec("DELETE FROM `presenters`");
		$db->exec("DELETE FROM `groups`");
		$db->exec("DELETE FROM `timeslots`");
		$db->exec("DELETE FROM `presentations`");
	}
	catch (Exception $e) {
		echo $e->getMessage() . '\n';
	}
}

function get_attr($db, $attr) {
	try {
		$sql = "SELECT `value` FROM `attributes` WHERE `attr` = :attr";
		$stmt = $db->prepare($sql);
		$stmt->execute(array(':attr' => $attr));
		$data_row = $stmt->fetch();

		return $data_row ? $data_row['value'] : '';
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
	return '';
}

function add_presentation($db, $title, $time_id, $group_id, $passwd) {

	try {

		$sql = "INSERT INTO `presentations` (`title`, `group_id`, `time_id`, `reg_time`) VALUES (:title, :group_id, :time_id, datetime('now'))";
		$stmt = $db->prepare($sql);
		$stmt->execute(array(':title' => $title, ':group_id' => $group_id, ':time_id' => $time_id));

		$sql = "UPDATE `timeslots` SET `occupied` = '1' WHERE `time_id` = :time_id";
		$stmt = $db->prepare($sql);
		$stmt->execute(array(':time_id' => $time_id));

		$sql = "UPDATE `groups` SET `registered` = '1', `password` = :password WHERE `group_id` = :group_id";
		$stmt = $db->prepare($sql);
		$stmt->execute(array(':group_id' => $group_id, ':password' => sha1($passwd . $group_id)));
	}
	catch (Exception $e) {
		echo $e->getMessage();
		return false;
	}

	return true;
}

function add_timeslot_by_rule($db, $rule) {
	// calculate interval
	$interval = 'P1D';
	if ($rule['repeat_mode'] == 'weekly') {
		$interval = 'P7D';
	}
	else if ($rule['repeat_mode'] == 'monthly') {
		$interval = 'P1M';
	}
	else if ($rule['repeat_mode'] == 'none') {
		$rule['end_date'] = clone $rule['start_date'];
	}

	try {
		$sql = "INSERT INTO `timeslots` (`date`, `start_time`, `end_time`, `time_order`, `occupied`) VALUES (:date, :start_time, :end_time, :time_order, 0)";
		$stmt = $db->prepare($sql);

		for ($itr_date = clone $rule['start_date']; $itr_date <= $rule['end_date']; $itr_date->add(new DateInterval($interval))) {

			for ($j = 0; $j < $rule['quota']; ++$j) {
				$stmt->execute(array(':date' => $itr_date->format('Y-m-d'), ':start_time' => $rule['start_time']->format('H:i'), ':end_time' => $rule['end_time']->format('H:i'), ':time_order' => ($j + 1)));
			}
		}
	}
	catch (Exception $e) {
		echo $e->getMessage();
		return false;
	}

	return true;

}

function add_new_group($db, $group_name) {
	$insert_group_sql = "INSERT INTO `groups` (`group_name`, `registered`) VALUES (:group_name, 0)";
	try {
		$stmt = $db->prepare($insert_group_sql);
		$stmt->execute(array(':group_name' => $group_name));
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
}

function get_group_id_by_name($db, $group_name) {

	$select_group_sql = "SELECT `group_id` FROM `groups` WHERE `group_name` = :group_name";

	try {
		$stmt = $db->prepare($select_group_sql);
		$stmt->execute(array(':group_name' => $group_name));
		$data_row = $stmt->fetch();
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}

	if (!$data_row) {
		add_new_group($db, $group_name);
		return get_group_id_by_name($db, $group_name);
	}

	return $data_row['group_id'];
}

function get_title($db) {
	$sql = "SELECT `value` FROM `attributes` WHERE `attr` = 'title'";

	try {
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$data_row = $stmt->fetch();

		if ($data_row) {
			return $data_row['value'];
		}
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}

	return '';
}


function get_members_by_group_id($db, $group_id) {
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
	try {
		$sql = "SELECT * FROM `presentations` WHERE `time_id` = :time_id";
		$stmt = $db->prepare($sql);
		$stmt->execute(array(':time_id' => $time_id));
		$data_row = $stmt->fetch();

		if ($data_row)
			return $data_row;
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
	return '';
}

function get_quota($db, $date, $begin_time, $end_time) {
	$sql = "SELECT COUNT(*) FROM `timeslots` WHERE `date` = :date AND `begin_time` = :begin_time AND `end_time` = :end_time";
	$stmt = $db->prepare($sql);
	$stmt->execute(array(':date' => $date, ':begin_time' => $begin_time, ':end_time' => $end_time));

	return $stmt->fetch()['COUNT(*)'];

}

function gen_random_password($len=8) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	return substr(str_shuffle($chars), 0, $len);
}
	
?>

