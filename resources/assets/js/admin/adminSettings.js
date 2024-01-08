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