// jscript for driver.php

$(document).ready(function() {


$(document).on('click', ".self_assign", function (e)
{
	var element = $(this);
	var order_id = $(this).attr('data-order_id');

	$.ajax({
		type: "POST",
		url: "./driver/selfAssignOrder",
		data: 'order_id=' + order_id,
		dataType: "json",
		success: function(data) 
		{
			if (data.form_check == 'error')
			{
				popup(data.alert);
			}
			else 
			{
				popup(data.alert);
				location.reload();
			}	
		},
		error: function (data)
		{
			generateError(data);
      	}
	});
	return false;
});


$(document).on('click', ".report_issue_init", function (e)
{
	var order = $(this).closest('.order_row')
	var order_id = $(this).attr('data-order_id');

	$(this).hide();
	order.find('.self_assign').hide();

	$(this).closest('.driver_order_row').append('<textarea class="block push-top wid-100" placeholder="What is wrong with the order?"></textarea><button class="report_issue push-top" data-order_id="'+order_id+'">Send Report</button>');

	return false;
});


$(document).on('click', ".report_issue", function (e)
{
	var order = $(this).closest('.order_row')
	var order_id = $(this).attr('data-order_id');
	var issue_text = order.find('textarea').val();

	$.ajax({
		type: "POST",

		url: './controllers/driver.php',
		data: 'action=report_issue&order_id=' + order_id + '&issue_text=' + issue_text,
		dataType: "json",
		success: function(data) 
		{
			console.log(data);
			if (data.form_check == 'error')
			{
				popup(data.alert);
			}
			else 
			{
				popup(data.alert);
				$('#driver-section').load(location.href + " #driver-section>*","");
			}	
		},
		error: function (xhr, ajaxOptions, thrownError) 
		{
			console.warn(xhr.responseText + thrownError);
      	}
	});

	return false;
});


$(document).on('click', ".mark-complete", function (e)
{
	e.preventDefault();
	var order_id = $(this).attr('data-order_id');
	var content = 'Confirm order #' + order_id + ' is complete?';

	var modal = new Modal('Update Order Status', null);
	modal.setContent(content);
	modal.submitButton.innerText = 'Mark Complete';
	modal.data = {order_id: order_id};
	modal.setVisible();

	modal.setSubmitAction(function() {
		var formData = this.data;
		$.ajax({
			type: "POST",
			url: "driver/markComplete",
			data: formData,
			dataType: "json",
			success: function(data)
			{
				if (data.error)
				{
					popup(data.alert);
				}
				else
				{
					popup(data.alert);
					this.destroy();

					model_destroy();
					popup(data.alert);

					$('#driver-section').load(location.href + " #driver-section>*","", function() {
						$('#' + order_id + '.manage-order').find('.manage-order-toggle').show();
					}, $(this));
				}
			}.bind(this),
			error: function (data)
			{
				generateError(data);
			}
		});
		return false;
	});

});

$(document).on('click', ".markComplete_verify", function (e)
{
	var element = $(this);
	var order_id = $(this).attr('data-order_id');

	$.ajax({
		type: "POST",
		url: "driver/markComplete",
		data:{
			"order_id": order_id,
		},
		dataType: 'json',
		success: function(data)
		{
			model_destroy();
			popup(data.alert);
			$('.orders_section').load(location.href + " .orders_section>*","");
		},
		error: function (xhr, ajaxOptions, thrownError)
		{
			console.warn(xhr.responseText + thrownError);
		}
	});

	return false;
});

setInterval(function(){
	showLoad = false;
	$(".order_status_wrap").load(location.href + " .order_status_wrap","");
}, 10000);


//$(document).on('click', ".update_cost", function (e)
//{
//	e.preventDefault();
//
//	var order_id = $(this).attr('data-order_id');
//	var order_cost = $(this).parent().children('input.textbox').val();
//
//	if (order_cost == "")
//	{
//		popup("Please specify an amount that you paid.");
//	}
//	else if (isNaN(parseFloat(order_cost)))
//	{
//		popup("That is not a valid input.");
//	}
//	else
//	{
//		order_cost = parseFloat(order_cost).toFixed(2);
//		content = "Please confirm that you paid<br /><h2>$" + order_cost + "</h2>";
//		content += '<div class="strong push-top push-bottom">Make sure you take a picture of the receipt!</div>';
//		content += '<br /><button class="update_cost_confirm" data-order_id="' + order_id + '" data-order_cost="' + order_cost + '">Confirm</button>';
//
//		model_create(content, false, 'align_center');
//
//	}
//
//	return false;
//});

//$(document).on('click', ".update_cost_confirm", function (e)
//{
//	var order_id = $(this).attr('data-order_id');
//	var order_cost = $(this).attr('data-order_cost');
//
//	$.ajax({
//		type: "POST",
//		url: "driver/updateOrderCost",
//		data:{
//			"order_id": order_id,
//			"order_cost": order_cost
//		},
//		dataType: 'json',
//		success: function(data)
//		{
//
//		},
//		error: function (data)
//		{
//			generateError(data);
//		}
//
//	});
//});


	$(document).on('click', '#initDriverUpdateCost', function(e)
	{
		e.preventDefault();

		var order_id = $(this).attr('data-order_id');
		var order_cost = $(this).parent().children('input.textbox').val();

		if (order_cost == "")
		{
			popup("Please specify an amount that you paid.");
		}
		else if (isNaN(parseFloat(order_cost)))
		{
			popup("That is not a valid input.");
		}
		else {
			order_cost = parseFloat(order_cost).toFixed(2);
			var content = "Please confirm that you paid<br /><h2>$" + order_cost + "</h2>";
			content += '<div class="strong push-top push-bottom">Make sure you take a picture of the receipt!</div>';
			content += '<br /><input type="hidden" class="update_cost_confirm" data-order_id="' + order_id + '" data-order_cost="' + order_cost + '" />';

			var modal = new Modal('Confirm Action', null);
			modal.setContent(content);
			modal.submitButton.innerText = 'Update Cost';
			modal.setVisible();
			modal.setSubmitAction(function () {
				var order_id = $(this.contentEl).find('input.update_cost_confirm').attr('data-order_id');
				var order_cost = $(this.contentEl).find('input.update_cost_confirm').attr('data-order_cost');
				var formData = "order_id=" + order_id + "&order_cost=" + order_cost;
				$.ajax({
					type: "POST",
					url: "driver/updateOrderCost",
					data: formData,
					dataType: "json",
					success: function (data) {
						if (data.error) {
							popup(data.alert);
						}
						else {
							popup(data.alert);
							this.destroy();

							if (data.error != undefined) {
								popup(data.alert);
							}
							else {
								popup(data.alert);
								model_destroy();
								$('#driver-section').load(location.href + " #driver-section>*", "");
							}
						}
					}.bind(this),
					error: function (data) {
						generateError(data);
					}
				});
				return false;
			});
		}
		return false;
	});



$(document).on('click', ".send-arrival-status", function (e)
{
	var order_id = $(this).closest('.order-row').attr('data-order_id');

	$.ajax({
		type: "POST",
		url: "driver/sendArrivalStatus",
		data:{
			"action": "send_arrival_status",
			"order_id": order_id
		},
		dataType: 'json',
		success: function(data)
		{
			popup(data.alert);
			$('#driver-section').load(location.href + " #driver-section>*","");
		},
		error: function (data)
		{
			generateError(data);
		}
	});
});








});