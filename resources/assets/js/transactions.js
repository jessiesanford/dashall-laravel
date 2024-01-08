$(document).ready(function() {

	var today = moment();
	getTransactions();

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