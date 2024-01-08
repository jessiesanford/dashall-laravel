$(document).ready(function() {


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

	$(function() {
		$("#start_date, #end_date").datepicker({
			dateFormat: 'MM dd yy',
			maxDate: moment(today).format('MMMM DD YYYY'),
			onSelect: function(date) {
     			getOrders();
     		}
		});
	});

	function getOrders()
	{
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
				success: function(data) 
				{
					$('#orders_view').fadeOut(200);
					$('#orders_view').empty();

					console.log(data);

					if (data.orders.length == 0)
					{
						popup('No results.');
					}
					else
					{
						var template = document.getElementById('order_row').innerHTML;

						orders_time_graph(data.order_stats.hot_hours);

						var orders = data.orders.data;

						$.each(data.orders, function(index, order)
						{
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
				error: function (data)
				{
		        	generateError(data);
		      	}
			});
			return false;
		
	}


	$(document).on('click', '#reset_orders', function() 
	{
		$("#start_date").val('');
		$("#end_date").val('');
		getOrders();
	});


	$(document).on('click', '.order-log-row', function()
	{
		if (!$(this).children('.order-info-bottom').is(':visible'))
		{
			$(this).addClass('selected');
			$(this).children('.order-info-bottom').stop(true, true).slideDown(200);
		}
		else 
		{
			$(this).removeClass('selected');
			$(this).children('.order-info-bottom').stop(true, true).slideUp(200);
		}
	});

	$(document).on('submit', '#admin-create-order', function(e)
	{
		e.preventDefault();
		console.log(e); // just testing git push

		$.ajax({
			type: "POST",
			url: 'admin/createTestOrder',
			dataType: "json",
			success: function(data)
			{
				if (data.error != true) {
					popup('Test order created.');
				}
			},
			error: function (data)
			{
				generateError(data);
			}
		});
		return false;
	});

});