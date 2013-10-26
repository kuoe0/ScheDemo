<?php
/**
 * Short description for db_config.php
 *
 * Copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 *
 * @package db_config
 * @author KuoE0 <kuoe0.tw@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 KuoE0 <kuoe0.tw@gmail.com>
 */


try {
	$db = new PDO('sqlite:PresentReg.db');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$db->exec("CREATE TABLE IF NOT EXISTS attributes (
		attr TEXT,
		value TEXT)");

	$db->exec("CREATE TABLE IF NOT EXISTS presenters (
		presenter_id TEXT PRIMARY KEY,
		group_id TEXT,
		name TEXT,
		registered INTEGER)");

	$db->exec("CREATE TABLE IF NOT EXISTS groups (
		group_id TEXT,
		members TEXT,
		registered INTEGER)");

	$db->exec("CREATE TABLE IF NOT EXISTS timeslots (
		time_id INTEGER PRIMARY KEY,
		begin TEXT,
		end TEXT,
		slice INTEGER,
		occupied INTEGER)");

	$db->exec("CREATE TABLE IF NOT EXISTS presentations (
		id INTEGER PRIMARY KEY,
		title TEXT,
		group_id INTEGER,
		time_id INTEGER,
		reg_time TEXT)");

}
catch (PDOException $e) {
	echo $e->getMessage();
}

?>

