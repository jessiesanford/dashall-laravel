$(document).ready(function() {

	$(document).on('change', '#driver_settings', function(e)
	{
		e.preventDefault();

		$.post('./controllers/driver.php', $('#driver_settings').serialize() + '&action=driver_settings',
		function(data)
		{
			if (data.form_check === 'error') {
				popup(data.alert);
			} 
			else {
				popup(data.alert);
			}

		}, 'json');

		return false;
	});

	$(document).on('click', '.confirm_shift', function(e) {
		e.preventDefault();

		var start_datetime = new Date($(this).attr('data-start'));
		var end_datetime = new Date($(this).attr('data-end'));

		var model_content = '<form id="confirm_shift_action"><div>' +
			'<input id="shift_start" name="shift_start" type="hidden" value="' + $(this).attr('data-start') + '">' +
			'<input id="shift_end" name="shift_end" type="hidden" value="' + $(this).attr('data-end') + '">' +
			'<i class="fa fa-3x fa-clock-o"></i></div>' +
			'<div class="push-bottom-20">Please confirm that you wish to take the following shift:</div>' +
			'Start: <strong>' + moment($(this).attr('data-start')).format('ddd, MMM Do @ h:mm a') + '</strong><br>' +
			'End: <strong>' + moment($(this).attr('data-end')).format('ddd, MMM Do @ h:mm a') + '</strong>' +
			'</form>';

		// model_create(, false, 'align_center');
        var modal = new Modal('Confirm Action', null);
        modal.setContent(model_content);
        $(modal.getElement()).addClass('align-center');
        modal.submitButton.innerText = 'Shift Me';
        modal.setVisible();
        modal.setSubmitAction(function () {

        	var formData = $('#confirm_shift_action').serialize();

			$.ajax({
				type: "POST",
				url: './schedule/takeShift',
				data: formData,
				dataType: "json",
				success: function(data)
				{
					if (data.form_check === 'error') {
						popup(data.alert);
					}
					else {
						popup(data.alert);
						modal.destroy();
						$("#shifts_table").load(location.href + " #shifts_table>*","");
						$("#calendar_weeks_view").load(location.href + " #calendar_weeks_view>*","");
					}
				},
				error: function(data) {
					generateError(data);
				}
			});

			model_destroy();
        });

	});



// order.php - submitting the order
$(document).on('submit', '#confirm_shift_action', function (e) {
	e.preventDefault();
	if ($(this).hasClass('shift_self'))
	{
		$(this).removeClass('shift_self');
		$(this).addClass('unassigned');
	}
	else if ($(this).hasClass('unassigned'))
	{
		$(this).removeClass('unassigned');
		$(this).addClass('shift_self');
	}

	$.ajax({
		type: "POST",
		url: './schedule/takeShift',
		data: $(this).serialize(),
		dataType: "json",
		success: function(data)
		{
			console.log(data);
			if (data.form_check == 'error') {
				popup(data.alert);
			}
			else {
				popup(data.alert);
				$("#shifts_table").load(location.href + " #shifts_table>*","");
				$("#calendar_weeks_view").load(location.href + " #calendar_weeks_view>*","");
			}
		},
		error: function(data) {
			generateError(data);
		}
	});

	model_destroy();
	
	return false;
});


// order.php - submitting the order
$(document).on('click', '.request-unshift', function (e)
{
	e.preventDefault();

	var shift_id = $(this).data('value');


	$.ajax({
		type: "POST",
		url: './schedule/reqUnshift',
		data: "shift_id=" + shift_id,
		dataType: "json",
		success: function(data) 
		{
			model_destroy();
			if (data.form_check == 'error')
			{
				popup(data.alert);
			}
			else 
			{
				popup(data.alert);
				$("#shifts_table").load(location.href + " #shifts_table>*","");
				$("#calendar_weeks_view").load(location.href + " #calendar_weeks_view>*","");
			}
		},
		error: function(data) {
			generateError(data);
		}
	});
	return false;
});



// order.php - submitting the order
$(document).on('click', '.remove-shift', function (e) {
	e.preventDefault();
	var el = $(this);

	var shift_id = $(this).closest('.driver_shift').attr('id');

	$(el).closest('.row').slideUp(200, function() {
		$.ajax({
			type: "POST",
			url: './admin/removeShift',
			data: "shift_id=" + shift_id,
			dataType: "json",
			success: function (data) {
				if (data.form_check == 'error') {
					popup(data.alert);
				}
				else {
					popup(data.alert);
					$("#shifts_table").load(location.href + " #shifts_table>*", "");
					$("#calendar_weeks_view").load(location.href + " #calendar_weeks_view>*", "");
				}
			},
			error: function (data) {
				generateError(data);
			}
		});
	});
	return false;
});


	// order.php - submitting the order
	$(document).on('submit', '#create-shift', function (e) {
		e.preventDefault();

		$.ajax({
			type: "POST",
			url: './schedule/createShift',
			data: $(this).serialize(),
			dataType: "json",
			success: function (data) {
				if (data.form_check == 'error') {
					popup(data.alert);
				}
				else {
					popup(data.alert);
					$(".week-row").load(location.href + " .week-row>*", "", function() {
						makeDraggable();
					});
				}
			},
			error: function (data) {
				generateError(data);
			}
		});

		return false;
	});


	makeDraggable();
	function makeDraggable() {
		$('.week-shift').draggable({
			revert: "invalid",
			revertDuration: 0,
			opacity: 0.6
		});
	}

	$('#delete-shift').droppable({
		hoverClass: "drag-delete",
		drop: function( event, ui ) {
			var shift = ui.draggable.context;
			var shift_data = ui.draggable.data();
			var params = {shift_day: shift_data.day, shift_start: shift_data.start, shift_end: shift_data.end};

			$(this).addClass("drag-delete");
			$(shift).fadeOut(200);

			$.ajax({
				type: "POST",
				url: './schedule/deleteShift',
				data: jQuery.param(params),
				dataType: "json",
				success: function (data) {
					if (data.form_check == 'error') {
						popup(data.alert);
						$(".week-row").load(location.href + " .week-row>*", "");
						makeDraggable();
					}
					else {
						popup(data.alert);
						$(shift).remove();
					}
				},
				error: function (data) {
					generateError(data);
				}
			});

			return false;
		}
	});


});