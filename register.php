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

$group_id = $_POST['group_id'];
$time_id = $_POST['time_id'];
$title = $_POST['title'];

$current = new DateTime(date('Y-m-d H:i', time()));
$begin_opening = new DateTime(date('Y-m-d H:i', time()));
$end_opening = new DateTime(date('Y-m-d H:i', time()));

$sql = "SELECT `value` FROM `attributes` WHERE `attr` = 'begin-opening'";
$stmt = $db->prepare($sql);
$stmt->execute();
$data_row = $stmt->fetch();

if ($data_row) {
	$begin_opening = new DateTime($data_row['value']);
}

$sql = "SELECT `value` FROM `attributes` WHERE `attr` = 'end-opening'";
$stmt = $db->prepare($sql);
$stmt->execute();
$data_row = $stmt->fetch();

if ($data_row) {
	$end_opening = new DateTime($data_row['value']);
}

$sql = "SELECT `value` FROM `attributes` WHERE `attr` = 'url'";
$stmt = $db->prepare($sql);
$stmt->execute();
$url = $stmt->fetch()['value'];

if ($current < $begin_opening) {
	echo '<h2>The system will open at ' . $begin_opening->format('Y-m-d H:i') . ' ~ ' . $end_opening->format('Y-m-d H:i') . '.</h2>';
	echo '<p>Redirect after 5 sec...</p>';
	header("Refresh: 5; URL=" . $url);
	die;
}
elseif ($current > $end_opening) {
	echo '<h2>The system had been closed.</h2>';
	echo '<p>Redirect after 5 sec...</p>';
	header("Refresh: 5; URL=" . $url);
	die;
}

$sql = "SELECT DISTINCT `registered` FROM `presenters` WHERE `group_id` = :group_id";
$stmt = $db->prepare($sql);
$stmt->execute(array(':group_id' => $group_id));

if ($stmt->fetch()['registered'] == '1') {
	echo '<h2>The presenters have registered. Please try again...</h2>';
	echo '<p>Redirect after 5 sec...</p>';
	header("Refresh: 5; URL=" . $url);
	die;
}

$sql = "SELECT DISTINCT `occupied` FROM `timeslots` WHERE `time_id` = :time_id";
$stmt = $db->prepare($sql);
$stmt->execute(array(':time_id' => $time_id));

if ($stmt->fetch()['occupied'] == '1') {
	echo '<h2>The time has been occupied. Please try again...</h2>';
	echo '<p>Redirect after 5 sec...</p>';
	header("Refresh: 5; URL=" . $url);
	die;
}

# generate random password
$passwd = gen_random_password();
echo "<h2>Your password: $passwd</h2>";
echo "<p>Please remember it! Go back to view <a href='$url'>result</a>.</p>";


$sql = "INSERT INTO `presentations` (`title`, `group_id`, `time_id`, `reg_time`) VALUES (:title, :group_id, :time_id, datetime('now'))";
$stmt = $db->prepare($sql);
$stmt->execute(array(':title' => $title, ':group_id' => $group_id, ':time_id' => $time_id));

$sql = "UPDATE `timeslots` SET `occupied` = '1' WHERE `time_id` = :time_id";
$stmt = $db->prepare($sql);
$stmt->execute(array(':time_id' => $time_id));

$sql = "UPDATE `presenters` SET `registered` = '1', `password` = :password WHERE `group_id` = :group_id";
$stmt = $db->prepare($sql);
$stmt->execute(array(':group_id' => $group_id, ':password' => sha1($passwd . $group_id)));

?>

