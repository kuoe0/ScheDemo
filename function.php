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

?>

