/*
 * main.js
 * Copyright (C) 2014 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 */

$(function() {


	$('#btn-setup').click(function () {
		window.location.href="setup.php";
	});

	$('#btn-presenter-list').change(function () {
		$('#presenter-list-filename').val(this.value.split('\\').pop());
	});

	$('#btn-time-list').change(function () {
		$('#time-list-filename').val(this.value.split('\\').pop());
	});
});

