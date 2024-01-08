function initAccountView() {
    initView();
};

// const accountInfo = new Vue({
//     el: '#account-info',
// });

$(document).ready(function()
{
	function initView() {

        var addressChange = false;

        if ((document.getElementById('account-address'))) {

            var addressAutocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById('account-address')),
                {types: ['geocode']});
            var location = {};
            var locationFormat = {
                street_number: 'short_name',
                route: 'long_name',
                locality: 'long_name',
                administrative_area_level_1: 'short_name',
                country: 'long_name',
                postal_code: 'short_name'
            };
            var locationData = {};

            addressAutocomplete.addListener('place_changed', function () {
                location = fillInAddress_(addressAutocomplete);

                Object.keys(location).forEach(function (key) {
                    if (locationFormat[key]) {
                        var val = location[key];
                        var val2 = location[key][locationFormat[key]];
                        locationData[key] = val2;
                    }
                });

                addressChange = true;
                document.getElementById('account-address').setAttribute('disabled', 'disabled');
            });
            geolocate_(addressAutocomplete);
        }

        document.getElementById('init-change-address').addEventListener('click', function (e) {
            e.preventDefault();
            var formattedAddressEl = document.getElementById('address-formatted');
            formattedAddressEl.style.display = 'none';
            document.getElementById('address-input-label').classList.remove('hidden');
            addressChange = true;
        });

        // updates first and lastname

        $(document).on('submit', '#updateSettings', function (e) {
            e.preventDefault();

            if (Object.keys(location).length === 0) {
                popup('Please provide a valid location.');
                return;
            }

            var formData = $('#updateSettings').serializeObject();
            formData['address'] = locationData;

            $.ajax({
                type: "POST",
                url: "user/updateInfo",
                data: formData,
                dataType: "json",
                success: function (data) {
                    if (data.form_check == 'error') {
                        console.log(data.error_source);
                        popup(data.alert);
                        $('input[name=' + data.error_source + ']').addClass('form_error');
                    }
                    else {
                        popup(data.alert);
                        $('#account-settings-view').fadeTo(200, 0.3);
                        $("#account-settings-view").load(location.href + " #account-settings-view>*", "", function () {
                            $("#account-settings-view").fadeTo(200, 1);
                        });
                    }
                },
                error: function (data) {
                    generateError(data);
                }
            });

            return false;
        });


        $(document).on('click', '#initChangeEmailAddress', function (e) {
            e.preventDefault();
            var modal = new Modal('Change Email Address', null);
            var changeEmailForm = '<change-email-form></change-email-form>';
            modal.setVue(changeEmailForm);
            modal.setVisible();
            modal.setSubmitAction(function () {
                $('.form_error').removeClass('form_error');
                var formData = $('#changeEmail').serialize();
                $.ajax({
                    type: "POST",
                    url: "user/changeEmail",
                    data: formData,
                    dataType: "json",
                    success: function (data) {
                        if (data.error) {

                            for (var key in data.invalid_inputs) {
                                $('input[name=' + key + ']').addClass('form_error');
                            }

                            popup(data.alert);
                        }
                        else {
                            popup(data.alert);
                            this.destroy();

                            $('#account-settings-view').fadeTo(200, 0.3);
                            $("#account-settings-view").load(location.href + " #account-settings-view>*", "", function () {
                                $("#account-settings-view").fadeTo(200, 1);
                            });
                        }
                    }.bind(this),
                    error: function (data) {
                        generateError(data);
                    }
                });
            });

        });


        $(document).on('click', '#initChangePhoneNumber', function (e) {
            e.preventDefault();
            var modal = new Modal('Change Phone Number', null);
            var changePhoneForm = '<change-phone-form></change-phone-form>';
            modal.setVue(changePhoneForm);
            modal.setVisible();
            modal.setSubmitAction(function () {
                var formData = $('#changePhone').serialize();
                $.ajax({
                    type: "POST",
                    url: "user/changePhone",
                    data: formData,
                    dataType: "json",
                    success: function (data) {
                        if (data.error) {
                            $('input[name=' + data.error_source + ']').addClass('form_error');
                            popup(data.alert);
                        }
                        else {
                            popup(data.alert);
                            this.destroy();

                            $('#account-settings-view').fadeTo(200, 0.3);
                            $("#account-settings-view").load(location.href + " #account-settings-view>*", "", function () {
                                $("#account-settings-view").fadeTo(200, 1);
                            });
                        }
                    }.bind(this),
                    error: function (data) {
                        generateError(data);
                    }
                });
                return false;
            });
        });


        $(document).on('submit', '#changePassword', function (e) {
            e.preventDefault();

            var form = $('#changePassword');
            var formData = form.serialize();

            $.ajax({
                type: "POST",
                url: "user/changePassword",
                data: formData,
                dataType: "json",
                success: function (data) {
                    if (data.error) {
                        $('input[name=' + data.error_source + ']').addClass('form_error');
                        popup(data.alert);
                    }
                    else {
                        popup(data.alert);
                        form[0].reset();
                    }
                }.bind(this),
                error: function (data) {
                    generateError(data);
                }
            });
            return false;
        });


        $(document).on('submit', '#change_address', function (e) {
            e.preventDefault();

            $.post('./controllers/user.php', $('#change_address').serialize() + '&action=user_change_address',
                function (data) {
                    if (data.form_check == 'error') {
                        $('input[name=' + data.error_source + ']').addClass('form_error');
                        popup(data.alert);
                    }
                    else {
                        popup(data.alert);
                    }
                }, 'json');

            return false;
        });

        $(document).on('submit', '#remove_payment_method', function (e) {
            e.preventDefault();
            return false;
        });


        $(document).on('click', '#initRemovePaymentMethod', function (e) {
            e.preventDefault();
            var modal = new Modal('Confirm Action', null);
            modal.setContent('Are you sure you want to remove this payment method?');
            modal.submitButton.innerText = 'Confirm';
            modal.setVisible();
            modal.setSubmitAction(function () {
                var formData = $('#changePhone').serialize();
                $.ajax({
                    type: "POST",
                    url: "user/removePaymentMethod",
                    data: formData,
                    dataType: "json",
                    success: function (data) {
                        if (data.error) {
                            popup(data.alert);
                        }
                        else {
                            popup(data.alert);
                            this.destroy();

                            //$('#account-settings-view').fadeTo(200, 0.3);
                            //$("#account-settings-view").load(location.href + " #account-settings-view>*", "", function () {
                            //	$("#account-settings-view").fadeTo(200, 1);
                            //});
                        }
                    }.bind(this),
                    error: function (data) {
                        generateError(data);
                    }
                });
                return false;
            });
        });


        $(document).on('submit', '#driver_settings', function (e) {
            e.preventDefault();

            $.post('./controllers/user.php', $('#driver_settings').serialize() + '&action=driver_settings',
                function (data) {
                    if (data.form_check == 'error') {
                        popup(data.alert);
                    }
                    else {
                        popup(data.alert);
                        window.location.href = './account';
                    }

                }, 'json');

            return false;
        });

    }

});
// admin settings js

$(document).ready(function() {

	$(document).on('submit', "#update_settings", function (e)
	{
		// var taking_orders_value = $('#taking_orders_select option:selected').val();
		form = $(this).serialize();
		update_settings(form);
		return false;
	});

	//$(document).on('change', "#update_settings", function (e)
	//{
	//	// var taking_orders_value = $('#taking_orders_select option:selected').val();
	//	form = $(this).serialize();
	//	update_settings(form);
	//	return false;
	//});

	function update_settings(data) {

		$.ajax({
			type: "POST",
			url: "admin/updateSettings",
			data: data,
			dataType: "json",
			success: function(data)
			{
				popup(data.alert);
				$("#settings_section form").fadeTo("fast", 0.4, function() {
					$("#settings_section").load(location.href + " #settings_section>*","", function() {});	
				});
			},
			error: function (data)
			{
	        	generateError(data);
	      	}
		});
	}
});
function initAdminOrdersView() {
	setupView();
}

$(document).ready(function() {

	function setupView() {

        getOrders();

        function orders_time_graph(hours) {

            google.charts.load('current', {packages: ['corechart', 'bar']});
            google.charts.setOnLoadCallback(drawBasic);


            function drawBasic() {

                var data = new google.visualization.DataTable();
                data.addColumn('timeofday', 'Time of Day');
                data.addColumn('number', 'Amount of Orders');

                for (var key in hours) {
                    var hour = hours[key].hour;
                    var count = hours[key].counter;
                    data.addRow([{v: [parseFloat(hour), 0, 0], f: parseFloat(hour) + ':00'}, parseFloat(count)]);
                }

                var options = {
                    title: 'Orders Per Hour',
                    hAxis: {
                        title: 'Time of Day',
                        format: 'h a',
                        viewWindow: {
                            min: [0, 30, 0],
                            max: [24, 30, 0]
                        }
                    },
                    vAxis: {
                        title: 'Amount Of Orders'
                    }
                };

                var chart = new google.visualization.ColumnChart(
                    document.getElementById('chart_div'));

                chart.draw(data, options);
            }
        }


        var today = moment();

        $(function () {
            $("#start_date, #end_date").datepicker({
                dateFormat: 'MM dd yy',
                maxDate: moment(today).format('MMMM DD YYYY'),
                onSelect: function (date) {
                    getOrders();
                }
            });
        });

        function getOrders() {
            start_date = new Date($("#start_date").val());
            end_date = new Date($("#end_date").val());

            start_date = moment(start_date).subtract(1, 'days').format('YYYY-MM-DD');
            end_date = moment(end_date).add(1, 'days').format('YYYY-MM-DD');

            if (isNaN(Date.parse(start_date)) || isNaN(Date.parse(end_date))) {
                console.log("changing dates");
                start_date = "0000-00-00";
                end_date = "9999-12-31";
            }


            $('.order_row').removeClass('orders_row_selected');

            $.ajax({
                type: "POST",
                url: 'admin/getOrderStats',
                data: {start_date: start_date, end_date: end_date},
                dataType: "json",
                success: function (data) {
                    $('#orders_view').fadeOut(200);
                    $('#orders_view').empty();

                    console.log(data);

                    if (data.orders.length == 0) {
                        popup('No results.');
                    }
                    else {
                        var template = document.getElementById('order_row').innerHTML;

                        orders_time_graph(data.order_stats.hot_hours);

                        var orders = data.orders.data;

                        $.each(data.orders, function (index, order) {
                            transaction = Mustache.render(template, order);
                            $('#orders_view').append(transaction);
                        });

                        $('#total_orders').text(data.order_stats.total_orders);
                        $('#total_complete_orders').text(data.order_stats.total_complete_orders);
                        $('#repeat_customer_count').text(data.order_stats.repeat_customer_data.count);
                        $('#repeat_customer_orders').text(data.order_stats.repeat_customer_data.sum);
                        $('#avg_order_time').text(data.order_stats.avg_order_time);

                        $('#orders_view').fadeIn(200);
                    }

                },
                error: function (data) {
                    generateError(data);
                }
            });
            return false;

        }


        $(document).on('click', '#reset_orders', function () {
            $("#start_date").val('');
            $("#end_date").val('');
            getOrders();
        });


        $(document).on('click', '.order-log-row', function () {
            if (!$(this).children('.order-info-bottom').is(':visible')) {
                $(this).addClass('selected');
                $(this).children('.order-info-bottom').stop(true, true).slideDown(200);
            }
            else {
                $(this).removeClass('selected');
                $(this).children('.order-info-bottom').stop(true, true).slideUp(200);
            }
        });

        $(document).on('submit', '#admin-create-order', function (e) {
            e.preventDefault();
            console.log(e); // just testing git push

            $.ajax({
                type: "POST",
                url: 'admin/createTestOrder',
                dataType: "json",
                success: function (data) {
                    if (data.error != true) {
                        popup('Test order created.');
                    }
                },
                error: function (data) {
                    generateError(data);
                }
            });
            return false;
        });

    }

});
function initTransactionsView() {
	getTransactions();
}

$(document).ready(function() {

	var today = moment();

	$(function() {
		$("#start_date, #end_date").datepicker({
			dateFormat: 'MM dd yy',
			maxDate: moment(today).format('MMMM DD YYYY'),
			onSelect: function(date) {
     			getTransactions();
     		}
		});
	});

	function getTransactions()
	{
		start_date = new Date($("#start_date").val());
		end_date = new Date($("#end_date").val());

		start_date = moment(start_date).subtract(1, 'days').format('YYYY-MM-DD');
		end_date = moment(end_date).add(1, 'days').format('YYYY-MM-DD');

		if (isNaN(Date.parse(start_date)) || isNaN(Date.parse(end_date))) {
			start_date = "0000-00-00";
			end_date = "9999-12-31";
		}

		$('.trans_row').removeClass('trans_row_selected');

		$.ajax({
			type: "POST",
			url: './admin/getTransactionStats',
			data: {start_date: start_date, end_date: end_date},
			dataType: "json",
			success: function(data)
			{
				console.log(data);
				$('#transactions_view').fadeOut(200).empty();

				if (data.trans == null || data.trans.length == 0)
				{
					popup('No results.');
				}
				else
				{
					var template = document.getElementById('transaction_row').innerHTML;

					$.each(data.trans, function(index, trans)
					{
						if (index <= 100) {
							transaction = Mustache.render(template, trans);
							$('#transactions_view').append(transaction);
						}
					});

					var trans_stats = data.trans_stats;

					$('#rev_amount').text('$' + trans_stats.revenue);
					$('#profit_amount').text('$' + trans_stats.profit);
					$('#firstdash_count').text(trans_stats.promo_count);
					$('#dashcash_count').text(trans_stats.dashcash_count);
					$('#order_count').text(trans_stats.order_count);
					$('#repeat_customer_orders').text(trans_stats.repeat_customer_orders);
					$('#avg_order_cost').text('$' + trans_stats.avg_order_cost);
					$('#avg_profit').text('$' + trans_stats.avg_order_profit);

					$('#transactions_view').fadeIn(200);
				}

			},
			error: function (data)
			{
				generateError(data);
			}
		});
		return false;

	}


	$(document).on('click', '#reset_transactions', function() 
	{
		$("#transactions_wrapper").load(location.href + " #transactions_wrapper>*","");
		$('#trans_stats').load(location.href + " #trans_stats>*","");
	});


	$(document).on('click', '.trans_row', function() 
	{
		if (!$(this).children('.trans_info').is(':visible'))
		{
			$(this).addClass('trans_row_selected no_border');
			$(this).children('.trans_info').stop(true, true).slideDown(200);
		}
		else 
		{
			$(this).removeClass('trans_row_selected no_border');
			$(this).children('.trans_info').stop(true, true).slideUp(200);
		}
	});

});
//# sourceMappingURL=views.js.map
