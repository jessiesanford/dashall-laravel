$(document).ready(function() 
{

	// updates first and lastname
	$(document).on('submit', '#updateSettings', function(e)
	{
		e.preventDefault();

		var formData = $('#updateSettings').serialize();

		$.ajax({
			type: "POST",
			url: "user/updateInfo",
			data: formData,
			dataType: "json",
			success: function(data)
			{
				if (data.form_check == 'error')
				{
					console.log(data.error_source);
					popup(data.alert);
					$('input[name=' + data.error_source + ']').addClass('form_error');
				}
				else
				{
					popup(data.alert);
					$('#account-settings-view').fadeTo(200, 0.3);
					$("#account-settings-view").load(location.href + " #account-settings-view>*", "", function () {
						$("#account-settings-view").fadeTo(200, 1);
					});
				}
			},
			error: function (data)
			{
				generateError(data);
			}
		});

		return false;
	});


	$(document).on('click', '#initChangeEmailAddress', function(e) {
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



	$(document).on('click', '#initChangePhoneNumber', function(e)
	{
		e.preventDefault();
		var modal = new Modal('Change Phone Number', null);
		var changePhoneForm = '<change-phone-form></change-phone-form>';
		modal.setVue(changePhoneForm);
		modal.setVisible();
		modal.setSubmitAction(function() {
			var formData = $('#changePhone').serialize();
			$.ajax({
				type: "POST",
				url: "user/changePhone",
				data: formData,
				dataType: "json",
				success: function(data)
				{
					if (data.error)
					{
						$('input[name=' + data.error_source + ']').addClass('form_error');
						popup(data.alert);
					}
					else
					{
						popup(data.alert);
						this.destroy();

						$('#account-settings-view').fadeTo(200, 0.3);
						$("#account-settings-view").load(location.href + " #account-settings-view>*", "", function () {
							$("#account-settings-view").fadeTo(200, 1);
						});
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



	$(document).on('submit', '#changePassword', function(e)
	{
		e.preventDefault();

		var form = $('#changePassword');
		var formData = form.serialize();

		$.ajax({
			type: "POST",
			url: "user/changePassword",
			data: formData,
			dataType: "json",
			success: function(data)
			{
				if (data.error)
				{
					$('input[name=' + data.error_source + ']').addClass('form_error');
					popup(data.alert);
				}
				else
				{
					popup(data.alert);
					form[0].reset();
				}
			}.bind(this),
			error: function (data)
			{
				generateError(data);
			}
		});
		return false;
	});


	$(document).on('submit', '#change_address', function(e)
	{
		e.preventDefault();

		$.post('./controllers/user.php', $('#change_address').serialize() + '&action=user_change_address',
		function(data)
		{
			if (data.form_check == 'error')
			{
				$('input[name=' + data.error_source + ']').addClass('form_error');
				popup(data.alert);
			} 
			else
			{	
				popup(data.alert);
			}
		}, 'json');

		return false;
	});

	$(document).on('submit', '#remove_payment_method', function(e)
	{
		e.preventDefault();
		return false;
	});



	$(document).on('click', '#initRemovePaymentMethod', function(e)
	{
		e.preventDefault();
		var modal = new Modal('Confirm Action', null);
		modal.setContent('Are you sure you want to remove this payment method?');
		modal.submitButton.innerText = 'Confirm';
		modal.setVisible();
		modal.setSubmitAction(function() {
			var formData = $('#changePhone').serialize();
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

						//$('#account-settings-view').fadeTo(200, 0.3);
						//$("#account-settings-view").load(location.href + " #account-settings-view>*", "", function () {
						//	$("#account-settings-view").fadeTo(200, 1);
						//});
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


	$(document).on('submit', '#driver_settings', function(e)
	{
		e.preventDefault();

		$.post('./controllers/user.php', $('#driver_settings').serialize() + '&action=driver_settings',
		function(data)
		{
			if (data.form_check == 'error')
			{
				popup(data.alert);
			} 
			else
			{	
				popup(data.alert);
				window.location.href = './account';
			}

		}, 'json');

		return false;
	});

});