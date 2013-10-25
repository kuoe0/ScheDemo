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

$group_id = $_POST['group_id'];
$time_id = $_POST['time_id'];
$title = $_POST['title'];

$sql = "INSERT INTO `presentations` (`title`, `group_id`, `time_id`, `reg_time`) VALUES (:title, :group_id, :time_id, datetime('now'))";
$stmt = $db->prepare($sql);
$stmt->execute(array(':title' => $title, ':group_id' => $group_id, ':time_id' => $time_id));

?>

