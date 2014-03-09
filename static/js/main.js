/*
 * main.js
 * Copyright (C) 2014 KuoE0 <kuoe0.tw@gmail.com>
 *
 * Distributed under terms of the MIT license.
 */

$(function() {

	function add_time() {
		var time_cnt = $('input[name="time-cnt"]').val();
		$('input[name="time-cnt"]').val(parseInt(time_cnt) + 1);

		var time_result = '<div id="column-time-' + time_cnt + '" class="column"> <div class="ui raised segment"> <div id="btn-delete-time-' + time_cnt + '" class="ui right red corner label"> <i class="delete basic icon"></i> </div> <div class="two fields"> <div class="field"> <label>Start Date</label> <div class="ui left icon input"> <input type="date" name="start-date-' + time_cnt + '" /> <i class="calendar icon"></i> </div> </div> <div class="field"> <label>End Date</label> <div class="ui left icon input"> <input type="date" name="end-date-' + time_cnt + '" /> <i class="calendar icon"></i> </div> </div> </div> <div class="two fields"> <div class="field"> <label>Start Time</label> <div class="ui left icon input"> <input type="time" name="start-time-' + time_cnt + '" /> <i class="time icon"></i> </div> </div> <div class="field"> <label>End Date</label> <div class="ui left icon input"> <input type="time" name="end-time-' + time_cnt + '" /> <i class="time icon"></i> </div> </div> </div> <div class="two fields"> <div class="field"> <label># of People</label> <div class="ui left icon input"> <input type="number" name="quota-' + time_cnt + '" /> <i class="users icon"></i> </div> </div> <div class="field"> <label>Repeat</label> <div class="ui fluid selection dropdown"> <input type="hidden" name="quota-' + time_cnt + '"/> <div class="text">Choose</div> <i class="dropdown icon"></i> <div class="menu"> <div class="item" data-value="none">None</div> <div class="item" data-value="daily">Daily</div> <div class="item" data-value="weekly">Weekly</div> <div class="item" data-value="monthly">Monthly</div> </div> </div> </div> </div> </div> </div>';

		$('#time-list').append(time_result);

		$('#btn-delete-time-' + time_cnt).click(function () {
			$('#column-time-' + time_cnt).remove();
		});

		$('.ui.dropdown').dropdown();
	}

	function add_presenter() {
		var presenter_cnt = $('input[name="presenter-cnt"]').val();
		$('input[name="presenter-cnt"]').val(parseInt(presenter_cnt) + 1);

		var presenter_result = '<div id="column-presenter-' + presenter_cnt + '" class="column"> <div class="ui raised segment"> <div id="btn-delete-presenter-' + presenter_cnt + '" class="ui right red corner label"> <i class="delete basic icon"></i> </div> <div class="three fields"> <div class="field"> <label>ID</label> <input type="text" name="presenter-id-' + presenter_cnt + '" /> </div> <div class="field"> <label>Name</label> <input type="text" name="presenter-name-' + presenter_cnt + '" /> </div> <div class="field"> <label>Group</label> <input type="text" name="presenter-group-' + presenter_cnt + '" /> </div> </div> </div> </div>';

		$('#presenter-list').append(presenter_result);

		$('#btn-delete-presenter-' + presenter_cnt).click(function () {
			$('#column-presenter-' + presenter_cnt).remove();
		});

	}

	function result_modal_after_submit(data) {

		console.log(data);
		data = JSON.parse(data);


		$('#result-modal .header').empty();
		$('#result-modal .content').empty();

		if (data.status) {
			$('#result-modal .header').append('Sucessed!');

			if (typeof(data.msg) != 'undefined') {
				$('#result-modal .content').append(data.msg);
			}

			$('#result-modal').modal({
				onHide: function () {
					location.reload();
				}
			});
		}
		else {
			$('#result-modal .header').append('Failed!');

			if (typeof(data.msg) != 'undefined') {
				$('#result-modal .content').append(data.msg);
			}

			$('#result-modal').modal({
				onHide: function () {}
			});
		}

		$('#result-modal').modal('show');
	}


	$('#btn-setup').click(function () {
		window.location.href = "setup.php";
	});

	$('input[name="presenter-list"]').change(function () {
		$('#presenter-list-filename').val($(this).val().split('\\').pop());
	});

	$('#remove-presenter-list').click(function () {
		$('input[name="presenter-list"]').val('');
		$('#presenter-list-filename').val('');
	});


	$('input[name="time-list"]').change(function () {
		$('#time-list-filename').val($(this).val().split('\\').pop());
	});

	$('#remove-time-list').click(function () {
		$('input[name="time-list"]').val('');
		$('#time-list-filename').val('');
	});

	$('#btn-index').click(function () {
		window.location.href = $('input[name="index"]').val();
	});

	$('#btn-add-time').click(add_time);
	$('#btn-add-presenter').click(add_presenter);

	$('.ui.register.button').click(function () {

		var time_id = $(this).attr('id').split('-').pop();

		if ($('#register-form').sidebar('is open')) {
			if ($('#register-form form input[name="time-id"]').val() != time_id) {
				$('#register-form').sidebar('hide');
			}
		}

		$('#register-form').sidebar('toggle');

		if ($('#register-form').sidebar('is open')) {
			$('#register-form form input[name="time-id"]').val(time_id);
			$('#register-form form input[name="date"]').val($('#date-' + time_id).text());
			$('#register-form form input[name="time"]').val($('#time-' + time_id).text());
			$('#register-form form input[name="order"]').val($('#order-' + time_id).text());
		}
	});

	$('.ui.cancel-register.button').click(function () {
		var time_id = $(this).attr('id').split('-').pop();

		if ($('#cancel-form').sidebar('is open')) {
			if ($('#cancel-form form input[name="time-id"]').val() != time_id) {
				$('#cancel-form').sidebar('hide');
			}
		}

		$('#cancel-form').sidebar('toggle');

		if ($('#cancel-form').sidebar('is open')) {
			$('#cancel-form form input[name="time-id"]').val(time_id);
		}
	});


	$('#btn-register').click(function () {
		var url = $('#register-form form').attr('action');
		var form_data = $('#register-form form').serialize();
		console.log(url);
		$.post(url, form_data, result_modal_after_submit);
	});

	$('#btn-register-exit').click(function () {
		$('#register-form').sidebar('hide');
	});

	$('#btn-cancel-register').click(function () {
		var url = $('#cancel-form form').attr('action');
		var form_data = $('#cancel-form form').serialize();
		console.log(url);
		$.post(url, form_data, result_modal_after_submit);
	});

	$('#btn-cancel-register-exit').click(function () {
		$('#cancel-form').sidebar('hide');
	});

	$(window).resize(function () {
		$('.vertical-centering').css({
			'position': 'absolute',
			'top': ($(window).height() - $('.vertical-centering').outerHeight()) / 3
		});
	});

	$(window).resize();
	$('.ui.modal').modal();
	$('.ui.dropdown').dropdown();


});

