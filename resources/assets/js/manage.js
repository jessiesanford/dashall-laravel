$(document).ready(function() {

	if (Cookies.get('lastOpenedOrder') && JSON.parse(Cookies.get('lastOpenedOrder')) instanceof Array)
	{
		var openOrders = JSON.parse(Cookies.get('lastOpenedOrder'));

		for(var i in openOrders)
		{
			var order_id = openOrders[i];
			var order = $('#' + order_id +'.manage-order');

			var orderTogglePane = $(order).closest('.manage-order').find('.manage-order-toggle');
			var orderContainer = $(order).closest('.manage-order-container');

			if (!orderTogglePane.is(":visible"))
			{
				orderContainer.addClass('toggled');
				orderTogglePane.show();
			}
		}
	}


	$(document).on('click', '.manage-filter-orders', function(e) {
		e.preventDefault();
		var modal = new Modal('Filter Orders', '');
		modal.setVue('<manage-filter-orders></manage-filter-orders>');
		modal.setVisible();
		modal.setSubmitAction(function () {

			var form = $('#manage-filter-orders');
			var formData = form.serializeArray();

			window.location.href = "./manage/filter?" + $.param(form.serializeArray());

			//$.ajax({
			//	type: "POST",
			//	url: "manage/filterOrders",
			//	data: formData,
			//	dataType: "json",
			//	success: function (data) {
            //
			//	}.bind(this),
			//	error: function (data) {
			//		generateError(data);
			//	}
			//});
		});

		$(document).on('change', 'input[type=checkbox]', function(e) {
			if ($(this).prop('checked') == true) {
				$(this).parent('label').addClass('checked');
			}
			else {
				$(this).parent('label').removeClass('checked');
			}
		});

	});


	// Open Order Status Model
	$(document).on('click', ".init-update-order-status", function (e)
	{
		var el = $(this);
		var order_id = $(this).attr('data-order_id');

		if ($(this).attr('id') == 'ms_COM' || $(this).attr('id') == 'ms_ARCH' || $(this).attr('id') == 'ms_CANC') {
			return false;
		}

		e.preventDefault();

		var content = '<h3>Change Status of Order #' + order_id + '</h3>';
		content += '<button data-order_id="' + order_id + '" class="update-order-status btn-lrg block width_full push-bottom-20" id="APP_S1">Approved</button>';
		content += '<button data-order_id="' + order_id + '" class="update-order-status btn-lrg block width_full push-bottom-20" id="DEN">Denied</button>';
		content += '<button data-order_id="' + order_id + '" class="mark-complete btn-lrg block width_full">Complete</button>';

		var modal = new Modal('Update Status', null);
		modal.setContent(content);
		modal.submitButton.innerText = 'Confirm';
		modal.setVisible();
		modal.toolbarEl.removeChild(modal.submitButton);
		modal.setSubmitAction(function() {
			$.ajax({
				type: "POST",
				url: "user/removePaymentMethod",
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

						$('#manage-section').load(location.href + " #manage-section>*","", function() {
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

		// TODO: add event listener for modal close
	});



	// Update Order Status
	$(document).on('click', ".update-order-status", function (e)
	{
		var order_id = $(this).attr('data-order_id');
		var order_form = '#'+ order_id;
		var order_status = ($(this).attr('id'));

		$.ajax({
			type: "POST",
			url: "./manage/updateOrderStatus",
			data:{
				"order_id": order_id,
				"status_id": order_status
			},
			dataType: "json",
			success: function(data)
			{
				model_destroy();
				popup(data.alert);
				$('#manage-section').load(location.href + " #manage-section>*","", function() {
					$('#' + order_id + '.manage-order').find('.manage-order-toggle').show();
				}, $(this));
			},
			error: function (data)
			{
				generateError(data);
			}
		});

		return false;
	});




	// Delete Order
	$(document).on('click', ".delete-order", function (e)
	{
		$.ajax({
			type: "POST",
			url: "./manage/deleteOrder",
			data:{"order_id": $(this).attr('data-value')},
			dataType: 'json',
			success: function(data)
			{
				model_destroy();
				popup(data.alert);
				$('#manage-section').load(location.href + " #manage-section>*","");
			},
			error: function (data)
			{
				generateError(data);
			}
		});

		return false;
	});




	// Toggle Order Visibility
	$(document).on('click', ".manage-order-heading", function (e)
	{
		var order = $(this).closest('.manage-order');
		var order_id = $(this).closest('.manage-order').attr('id');
		var orderTogglePane = $(this).closest('.manage-order').find('.manage-order-toggle');
		var orderContainer = $(this).closest('.manage-order-container');

		if (Cookies.get('lastOpenedOrder')) {
			var openedOrders = JSON.parse(Cookies.get('lastOpenedOrder'));
		} else {
			var openedOrders = [];
		}

		if (orderTogglePane.is(":visible"))
		{
			if (openedOrders.indexOf() != -1) {
				openOrders.remove(order_id);
			}
			Cookies.set('lastOpenedOrder', JSON.stringify(openedOrders));

			orderContainer.removeClass('toggled');
			orderContainer.find('.manage-order-heading.fixed').remove();
			orderTogglePane.hide();
		}
		else
		{
			if (openedOrders.indexOf() == -1) {
				openedOrders.push(order_id);
			}
			Cookies.set('lastOpenedOrder', JSON.stringify(openedOrders));

			orderContainer.addClass('toggled');
			orderTogglePane.slideDown(200);
		}

		return false;
	});

	$(document).on('click', '.manage-order-heading.fixed', function() {
		var activeOrder = $('.manage-order-container.toggled');
		$('body').scrollTop(activeOrder.offset().top - 10);
	});

	//$(document).on( 'scroll', function(){
	//	var activeOrder = $('.manage-order-container.toggled');
	//	var activeOrderHeading = activeOrder.find('.manage-order-heading');
	//	var activeOrderHeadingFixed = activeOrder.find('.manage-order-heading.fixed');
	//	var orderIsToggled = $('.manage-order-toggle:visible');
    //
	//	// clone and fix only if
	//	// 1. Order is toggled
	//	// 2. we are scrolling over the toggled body
    //
	//	var toggledOrders = activeOrder.length > 0;
	//	var headingIsFixed = activeOrderHeadingFixed.length > 0;
	//	var activeOrderIsInView = (activeOrder.offset().top) <= $('body').scrollTop() && $('body').scrollTop() < activeOrder.offset().top + activeOrder.height()
    //
	//	if (toggledOrders && headingIsFixed && activeOrderIsInView) {
	//		var activeOrderHeadingFixed = activeOrder.find('.manage-order-heading').clone();
	//		activeOrderHeadingFixed.appendTo(activeOrder);
	//		activeOrderHeadingFixed.addClass('fixed');
	//	}
	//	else if (activeOrder.length > 0 && activeOrder.offset().top > $('body').scrollTop()) {
	//		activeOrderHeadingFixed.remove();
	//	}
	//	else if (activeOrder.length > 0 && activeOrderHeadingFixed.length > 0 && activeOrder.offset().top + activeOrder.height() < $('body').scrollTop()) {
	//		activeOrderHeadingFixed.remove();
	//	}
    //
	//});


	// Edit Order Info
	(function($)
	{
		var order;
		var order_id;
		var order_info_form_els;

		$(document).on('click', ".edit-order", function (e)
		{
			order = $(this).closest('.manage-order');
			order_id = order.attr('id');
			order_info_form_els = order.find('form.manage-order-form').find('.form-input');
			order.find('.manage-order-form').addClass('edit-mode');

			$(this).parent().append('<button class="cancel-edit-order" type="reset">Cancel</button>');

			$(order_info_form_els).each(function(el) {
				var value = $(this).text();
				if ($(this).data('value') == 'summary') {
					$(this).empty().html('<textarea name="summary" class="textarea" rows="4">' + value + '</textarea>');
				} else {
					$(this).empty().html('<input class="textbox block" name="' + $(this).attr('class').split(' ')[0] + '" class="textarea" rows="4" value="'+value+'" />');
				}
			});

			$(this).html('<i class="fa fa-check-circle push-right"></i> Save').removeClass().addClass('submit-order-changes')
				.attr('data-action', 'submit-order-changes')
				.attr('data-desc', 'Confirm Order Changes')
				.attr('data-order_id', order_id);

			return false;
		});




		$(document).on('click', ".cancel-edit-order", function (e)
		{
			e.preventDefault();

			order = $(this).closest('.manage-order');
			order_id = order.attr('id');
			order_info_form_els = order.find('form.manage-order-form').find('input, textarea');
			order.find('.manage-order-form').removeClass('edit-mode');

			$(order_info_form_els).each(function(el) {
				var value = $(this).val();
				var container = $(this).parent('.form-input');
				container.empty();
				container.text(value);
				//$(this).empty().html('<input class="textbox block" name="' + $(this).attr('class').split(' ')[0] + '" class="textarea" rows="4" value="'+value+'" />');
			});

			$('.submit-order-changes').html('<i class="fa fa-pencil push-right"></i> Edit').removeClass().addClass('edit-order');
			$(this).remove();

			return false;
		});



		//$(document).on('click', ".make-editable", function (e)
		//{
		//	e.preventDefault();
        //
		//	order = $(this).closest('.manage-order');
		//	order_id = order.attr('id');
		//	field = $(this).data('field');
        //
		//	//$(this).parent().append('<button type="reset">Cancel</button>');
		//	//order.find('.manage-order-form').addClass('edit-mode');
        //
		//	var inputEl = $(order).find('.form-input[data-value=' + field + ']');
		//	var inputElVal = inputEl.text();
        //
		//	if ($(inputEl).data('value') == 'summary') {
		//		$(inputEl).empty().append('<textarea name="summary" class="textarea" rows="4">' + inputElVal + '</textarea>');
		//	}
		//	else {
		//		$(inputEl).empty().append('<input class="textbox block" name="' + $(this).attr('class').split(' ')[0] + '" class="textarea" rows="4" value="'+ inputElVal +'" />');
		//	}
        //
		//	$(this).closest()
		//	$(this).text('Cancel').removeClass('make-editable').addClass('smalltext make-editable-revert');
        //
		//	return false;
		//});


		// Submit Order Info
		$(document).on('click', '.submit-order-changes', function(e) {
			loadingStart();
			e.preventDefault();

			var formEl = $('#'+ $(this).attr('data-order_id') + ' .manage-order-form');
			formEl.removeClass('edit-mode');

			var order_form = '#'+ $(this).attr('data-order_id');
			var form_data = formEl.serialize();
			var order_id = $(this).data('order_id');
			$.ajax({
				type: "POST",
				url: "./manage/updateOrderInfo",
				data: form_data + "&order_id=" + order_id,
				dataType: 'json',
				success: function (data) {
					popup(data.alert);
					$('#manage-section').load(location.href + " #manage-section>*","", function() {
						$('#' + order_id + '.manage-order').find('.manage-order-toggle').show();
					}, $(this));
				},
				error: function (data) {
					generateError(data);
				}
			});
			return false;
		});



	})(jQuery);



	// Edit Order Costs
	(function($)
	{
		var order;
		var order_id;
		var amount;
		var margin;
		var delivery_fee;
		var discount;

		$(document).on('click', ".edit-order-cost", function (e) {
			e.preventDefault();

			order = $(this).closest('.manage-order');
			order_id = order.attr('id');
			amount = order.find('.cost_amount span');
			margin = order.find('.cost_margin span');
			delivery_fee = order.find('.cost_delivery_fee span');
			discount = order.find('.cost_discount span');
			$(this).html('<i class="fa fa-check-circle"></i>').removeClass('edit-order-cost').addClass('submit-order-cost-changes').attr('data-order_id', order_id);

			amount.replaceWith($('<input/>', {
				'type': 'text',
				'name': 'amount',
				'class': 'textbox block push-bottom',
				'value': amount.text()
			}));

			margin.replaceWith($('<input/>', {
				'type': 'text',
				'name': 'margin',
				'class': 'textbox block push-bottom',
				'value': margin.text()
			}));

			delivery_fee.replaceWith($('<input/>', {
				'type': 'text',
				'name': 'delivery_fee',
				'class': 'textbox block push-bottom',
				'value': delivery_fee.text()
			}));

			discount.replaceWith($('<input/>', {
				'type': 'text',
				'name': 'discount',
				'class': 'textbox block push-bottom',
				'value': discount.text()
			}));
		});

		$(document).on('click', '.submit-order-cost-changes', function (e)
		{
			loadingStart();

			e.preventDefault();
			var order_id = $(this).data('order_id');
			var form_data = order.find('form.manage-order-costs').serialize();
			$.ajax({
				type: "POST",
				url: "./manage/updateOrderCosts",
				data: form_data + "&order_id=" + order_id,
				dataType: 'json',
				success: function (data) {
					popup(data.alert);
					model_destroy();
					$('#manage-section').load(location.href + " #manage-section>*", "", function () {
						$('#' + order_id + '.manage-order').find('.manage-order-toggle').show();
					}, $(this));
				},
				error: function (data) {
					generateError(data);
				}
			});
			return false;
		});
	})(jQuery);


	$(document).on('click', ".assign-driver", function (e)
	{
		loadingStart();
		order = $(this).closest('.manage-order');
		order_id = order.attr('id');
		driver_id = $(order).find('.select_driver option:selected').val();

		$.ajax({
			type: "POST",
			url: "./manage/assignDriver",
			data: {"order_id": order_id, "driver_id": driver_id},
			dataType: 'json',
			success: function(data)
			{
				popup(data.alert);
				$('#manage-section').load(location.href + " #manage-section>*","", function() {
					$('#' + order_id + '.manage-order').find('.manage-order-toggle').show();
				}, $(this));
			},
			error: function (data)
			{
				generateError(data);
			}
		});
		return false;
	});

	$(document).on('click', ".unassign-driver", function (e)
	{
		loadingStart();
		order = $(this).closest('.manage-order');
		order_id = order.attr('id');

		$.ajax({
			type: "POST",
			url: "./manage/unassignDriver",
			data: {"order_id": order_id},
			dataType: 'json',
			success: function(data)
			{
				popup(data.alert);
				$('#manage-section').load(location.href + " #manage-section>*","", function() {
					$('#' + order_id + '.manage-order').find('.manage-order-toggle').show();
				}, $(this));
			},
			error: function (data)
			{
				generateError(data);
			}
		});
		return false;
	});



	$(document).on('click', ".collect-payment", function (e)
	{
		var order = $(this).closest('.manage-order');
		var order_id = order.attr('id');

		$.ajax({
			type: "POST",
			url: "./manage/collPayment",
			data:{"order_id": order_id},
			dataType: "json",
			success: function(data)
			{
				console.log(data);
				if (data.form_check == 'error')
				{
					popup(data.alert);
					$('textarea[name=' + data.error_source + ']').addClass('form_error');
				}
				else
				{
					popup(data.alert);
					$('form#' + order_id + '.manage-order').load(location.href + " " + ('form#' + order_id + '.manage-order') + ">*","");
				}
			},
			error: function (data)
			{
				generateError(data);
			}
		});

		return false;
	});


	$(document).on('click', '.order_text', function(e) {
		user = $(this).closest('.info').find('.customer_name').text();
		phone = $(this).data('phone');
		content = '<form id="send_user_text">Send a text to ' + user + ' (+' + phone + ')';
		content += '<input name="phone" type="hidden" value="' + phone + '"/>';
		content += '<textarea name="message" class="width_full block push-top" placeholder="Write your message..."></textarea>';
		content += '<button class="push-top" type="submit">Send Text</button></form>';
		model_create(content, false, 'align_center');
		return false;
	});

	$(document).on('submit', '#send_user_text', function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: './manage/sendUserText',
			data: form_data,
			dataType: "json",
			success: function(data)
			{
				popup(data.alert);
				model_destroy();
			},
			error: function (data)
			{
				generateError(data);
			}
		});
	});

});