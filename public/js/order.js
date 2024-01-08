$(document).ready(function() {

	$(function() {
		var availableTags = [
		  "Mcdonald's",
		  "Tim Horton's",
		  "Wendy's",
		  "KFC",
		  "A&W",
		  "Dairy Queen",
		  "Dominos",
		  "Subway",
		  "Poyo Tacos",
		  "Celtic Hearth",
		  "The Big's Ultimate",
		  "Jungle Jim's",
		  "Jack Astor's",
		  "Sushi Island",
		  "Sun Sushi",
		  "SushiNami Royale",
		  "Starbucks",
		  "Swiss Chalet",
		  "Mr. Sub"
		];
		$("#dashbox_location").autocomplete({
		  source: availableTags,
			change: function(e, ui) {
				val = $('.dashbox_textbox').val();
				$('#dashbox_button').text('DASH ' + val);
			}
		});
	});

	//$( "#dashbox_location" ).on( "autocompletechange", function( event, ui ) {
	//	val = $('.dashbox_textbox').val();
	//	$('#dashbox_button').text('DASH ' + val);
	//} );
    //
    //
	//$(document).on('propertychange change click keyup input paste', '.dashbox_textbox', function(e) {
	//	val = $('.dashbox_textbox').val();
	//	$('#dashbox_button').text('DASH ' + val);
	//});

	(function() {
		var form_data;
		var meal_prompt_read = false;

		$(document).on('submit', "#dashbox", function (e) {
			loadingStart();
			e.preventDefault();
			ga('send', 'event', 'functions', 'init_order', 'init_order', '0');

			form_data = $(this).serialize();

			if (/meal|Meal/.test($(this).find("#dashbox_summary").val()) && meal_prompt_read == false) {
				content = "Hey! We noticed you specified a meal in your order description. Please specfiy any drink or additional details about the meal(s) if you haven't already!"
				content += '<br /><br /><a href="#" class="button model_destroy_trigger">Got It</a>'
				model_create(content, false, 'align_center');
				meal_prompt_read = true;
				return false;
			}


				$.ajax({
					type: "POST",
					url: './order/create',
					data: form_data + "&action=order_init",
					dataType: "json",
					success: function (data) {
						if (data.form_check == 'error') {
							popup(data.alert);
							$('textarea[name=' + data.error_source + ']').addClass('form_error');
						}
						else {
							popup(data.alert);

							$("#order-flow").fadeOut(200);
							$("#order-flow").load(location.href + " #order-flow>*", "", function () {
								$("#order-flow").fadeIn(200);
							});

							$('html, body').animate({
								scrollTop: $("#order-flow").offset().top
							}, 500);
						}
					},
					error: function (data) {
						//console.warn(xhr.responseText)
						generateError(data);
					}
				});
				return false;

		});


		$(document).on('click', "#dashbox_button_confirm", function (e) {
			loadingStart();
			e.preventDefault();
			$.ajax({
				type: "POST",
				url: './controllers/order.php',
				data: form_data.serialize() + "&action=order_init_confirm",
				dataType: "json",
				success: function (data) {
					if (data.form_check == 'error') {
						console.log(data);
						popup(data.alert);
					}
					else {
						$("#order-flow").fadeOut(200);
						$("#order-flow").load(location.href + " #order-flow>*", "", function () {
							$("#order-flow").fadeIn(200);
						});
						model_destroy();

						$('html, body').animate({
							scrollTop: $("#order-flow").offset().top
						}, 500);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.warn(xhr.responseText)
				}
			});
			return false;
		});

	})();





	$(document).on('click keyup', '.add-order-item', function(e) {
		e.preventDefault();
		var orderItem = $(this).closest('.order-item-block');
		var val = orderItem.find('input').val();
		var itemCount = $('.order-items input').length + 1;

		if (val !== '') {
            orderItem.remove();
            var orderItemParsed = '<div class="order-item-block-parsed"><div class="wid-100"><input type="text" value="' + val + '" name="order_items[]" readonly /></div>' +
                '<div class="cell-right"><a class="remove-order-item"><i class="fa fa-minus-circle"></i></a></div></div>';
            $('.order-items').append(orderItemParsed);
            var orderItem = '<div class="order-item-block"><div class="wid-100"><input name="order_items[]" class="textbox wid-100 push-bottom-10" placeholder="Item Description" /></div>' +
                '<div><button class="btn-alt add-order-item"><i class="fa fa-plus-circle"></i></button></div></div>';
            $('.order-items').append(orderItem);
        }
        else {
			popup('fuck you');
		}
	});

	$(document).on('click', '.remove-order-item', function(e) {
		e.preventDefault();
		$(this).closest('.order-item-block-parsed').remove();
	});



	// order.php - submitting the order
	$(document).on('click', "#deactivate_order", function (e)
	{
		$.ajax({
			type: "POST",
			url: './controllers/order.php',
			data: "action=order_deactivate",
			dataType: "json",
			success: function(data)
			{
				$("#order-flow").load(location.href + " #order-flow>*","", function(){
					$("#order-flow").fadeIn(200);
					$('html, body').animate({
						scrollTop: $("#order-flow").offset().top
					}, 500);
				});
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
				console.warn(xhr.responseText)
			}
		});
		return false;
	});



	// order.php - cancel the order
	$(document).on('click', ".cancel_order_init", function (e)
	{
		e.preventDefault();

		var content = "<form id='cancel-order-confirm'>";
		content += "<div class='push-bottom-20'>We're sorry to see you go! If you could tell us why you're cancelling we will use your input to improve the service we provide.</div>";
		content += "<textarea class='width_full' placeholder='I am cancelling because...' name='cancel-reason'></textarea></form>";
		//model_create(content, false, "align_center");

		var modal = new Modal('Cancel Order', null);
		modal.setContent(content);
		modal.submitButton.innerText = 'Cancel Order';
		modal.setVisible();
		modal.setSubmitAction(function() {
			//var formData = $('#changePhone').serialize();
			loadingStart();
			e.preventDefault();

			var form_data = $(this).serialize();

			$.ajax({
				type: "POST",
				url: './order/cancel',
				data: form_data,
				dataType: "json",
				success: function(data) {
					if (data.form_check == 'error') {
						popup(data.alert);
					}
					else {
						popup(data.alert);
						this.destroy();
						$("#order-flow").hide(0);
						$("#order-flow").load(location.href + " #order-flow>*", "", function () {
							$("#order-flow").fadeIn(200);
							$('html, body').animate({
								scrollTop: $("#order-flow").offset().top
							}, 500);
						});
					}
				}.bind(this),
				error: function (data)
				{
					generateError(data);
				}
			});

			model_destroy();

			return false;
		});

		return false;
	});




	$(document).on('click', '.order-step-back', function(e) {
		e.preventDefault();

		$.ajax({
			type: "POST",
			url: 'order/stepBack',
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
					$("#order-flow").hide(0);
					$("#order-flow").load(location.href + " #order-flow>*","", function(){
						$("#order-flow").fadeIn(200);
						$('html, body').animate({
							scrollTop: $("#order-flow").offset().top
						}, 500);
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



	// order.php - submitting the order
	$(document).on('submit', "#submit-order-details", function (e)
	{
		loadingStart();
		e.stopPropagation();

		var form_data = $(this).serialize();

		$.ajax({
			type: "POST",
			url: './order/submitDetails',
			data: form_data,
			dataType: "json",
			success: function(data)
			{
				if (data.error == true)
				{
					popup(data.alert);
					$('#submit_address').children('input[type=submit]').prop('disabled', false);
				}
				else
				{
					ga('send', 'event', 'functions', 'submit_order', 'submit_order', '2');
					popup(data.alert);
					$("#order_area_wrap").hide(0);
					$("#order_area_wrap").load(location.href + " #order_area_wrap>*","", function(){
						$("#order_area_wrap").fadeIn(200);
						$('html, body').animate({
							scrollTop: $("#order-flow").offset().top
						}, 500);
					});
				}
			}, error: function (data)
			{
				generateError(data);
			}
		});
		return false;
	});



// order.php - submitting the order
$(document).on('submit', "#submit-address", function (e)
{
	loadingStart();
	e.stopPropagation();

	var form_data = $(this).serialize();

	//$('#submit_address').children('input[type=submit]').prop('disabled', true);

	$(this).children('form-error').removeClass('error');

	$.ajax({
		type: "POST",
		url: './order/submitAddress',
		data: form_data,
		dataType: "json",
		success: function(data)
		{
			if (data.error == true)
			{
				popup(data.alert);
				$('#address-street').addClass('form-error');
			}
			else 
			{
	  			ga('send', 'event', 'functions', 'submit_order', 'submit_order', '2');
				popup(data.alert);
				$("#order_area_wrap").hide(0);
				$("#order_area_wrap").load(location.href + " #order_area_wrap>*","", function(){
					$("#order_area_wrap").fadeIn(200);	
					$('html, body').animate({
						scrollTop: $("#order-flow").offset().top
				    }, 500);
				});
			}
		}, error: function (data)
		{
			generateError(data);//console.log(xhr.responseText);`
		}
	});
	return false;
});



setInterval(function(){
	showLoad = false;

	if ($('.order-processing').length > 0) {
		$.get(location.href, function (response) {
			var el = '.order-processing';
			var newContent = $(el, response).html();
			var oldContent = $(el).html();
			if (oldContent != newContent) {
				$(el).load(location.href + " .order-processing>*", "");
			}
		});
	}

}, 5000);


// Stripe Publishable Key - TEST KEY
Stripe.setPublishableKey('pk_test_GnWoYt2RHjnjIOWSmYdE8Wjz');
// Stripe.setPublishableKey('pk_live_y3TWKdVCTUXH4VXTJ7rXvO7d');



// stripeReponseHandler - validates credit card info returns error(s) if found.
function stripeResponseHandler(status, response) 
{
	var form = $('#order_pay_auth');

    if (response.error)
    {

        $('#order_pay_auth').find('input, button').removeAttr("disabled");
        popup(response.error.message);

		if (response.error.param == 'number') {
			form.find('input.card-number').addClass('form-error');
		}
		else if (response.error.param == 'exp_month') {
			form.find('input.card-expiry-month').addClass('form-error');

		}
		else if (response.error.param == 'exp_year') {
			form.find('input.card-expiry-year').addClass('form-error');
		}
    }
    else 
    { 
		loadingStart();

        // token contains id, last4, and card type
        var token = response['id'];
        // insert the token into the form so it gets submitted to the server
        form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");

		$.ajax({
			type: "POST",
			url: './order/validateCreditCard',
			data: form.serialize(),
			dataType: "json",
			success: function(data)
			{
				if (data.form_check == 'error')
				{
					popup(data.alert);
					$('#order_pay_auth').find('input, button').removeAttr("disabled");
				}
				else 
				{
					popup(data.alert);
					$("#order_area_wrap").load(location.href + " #order_area_wrap>*","", function() {
						$('html, body').animate({
							scrollTop: $("#order-flow").offset().top
					    }, 500);
					});
				}  
			}, error: function (data)
			{
				generateError(data);
				//console.log(xhr.responseText);
			}
		})
		.always(function() {
			loadingEnd();
		});
    }
}

// order_pay_auth - Runs user provided credit card information through stripeResponseHandler to check for validity.
$(document).on('submit', "#order_pay_auth", function (e)
{
	loadingStart();
	e.preventDefault();

	// $(this).find('input, button').attr("disabled", "disabled");

	Stripe.createToken(
	{
	    number: $('.card-number').val(),
	    cvc: $('.card-cvc').val(),
	    exp_month: $('.card-expiry-month').val(),
	    exp_year: $('.card-expiry-year').val()
	}, stripeResponseHandler);

	loadingEnd();
	return false; 
});

// order_pay_auth_logged - If we have a Stripe Customer ID for the account we process the pre-auth using this method.
$(document).on('submit', "#order_pay_auth_logged", function (e)
{
	e.preventDefault();
	loadingStart();

	$(this).children('input, button').attr("disabled", "disabled");
	$.ajax({
		type: "POST",
		url: './order/authCreditCard',
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
				$("#order_area_wrap").load(location.href + " #order_area_wrap>*","", function() {
					$('html, body').animate({
						scrollTop: $("#order-flow").offset().top
				    }, 500);
				});
			}  
		},
		error: function (data)
		{
			generateError(data);
		}
	})
	.always(function() {
		loadingEnd();
	});

	return false; 
});

$(document).on('click', ".delete_credit_card", function (e)
{
	loadingStart();
	e.preventDefault();
	model_destroy();
	$.ajax({
		type: "POST",
		url: './order/deleteCreditCard',
		data: {"action": "delete_credit_card"},
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
				$("#order_area_wrap").load(location.href + " #order_area_wrap>*","", function() {
					$('html, body').animate({
						scrollTop: $("#order-flow").offset().top
					}, 500);
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

// part of order promo
$(document).on('click', '.select_box', function() {
	$(this).closest('.select_area').find('.select_box.selected').removeClass('selected');
	$(this).addClass('selected');
});

// order.php - cancel the order
$(document).on('submit', "#order_promo", function (e)
{
	loadingStart();
	e.preventDefault();

	var promo_method = $(this).find('.select_box.selected').attr('id');
	var promo_data = $(this).find('.select_box.selected').find('#promo_data').val();

	$.ajax({
		type: "POST",
		url: './order/applyPromo',
		data: {promo_method: promo_method, promo_data: promo_data},
		dataType: "json",
		success: function(data)
		{
			if (data.error == true)
			{
				popup(data.alert);
			}
			else 
			{
				popup(data.alert);
				$("#order-flow").hide(0);
				$("#order-flow").load(location.href + " #order-flow>*","", function(){
					$("#order-flow").fadeIn(200);
					$('html, body').animate({
						scrollTop: $("#order-flow").offset().top
				    }, 500);
				});	
			}
		},
		error: function (data)
		{
			generateError(data);
			//console.log(xhr.responseText);
		}
	});

	return false;
});






// Starrr plugin (https://github.com/dobtco/starrr)
var __slice = [].slice;

(function($, window) {
    var Starrr;

    Starrr = (function() {
        Starrr.prototype.defaults = {
            rating: void 0,
            numStars: 5,
            change: function(e, value) {}
        };

        function Starrr($el, options) {
            var i, _, _ref,
                _this = this;

            this.options = $.extend({}, this.defaults, options);
            this.$el = $el;
            _ref = this.defaults;
            for (i in _ref) {
                _ = _ref[i];
                if (this.$el.data(i) != null) {
                    this.options[i] = this.$el.data(i);
                }
            }
            this.createStars();
            this.syncRating();
            this.$el.on('mouseover.starrr', 'i', function(e) {
                return _this.syncRating(_this.$el.find('i').index(e.currentTarget) + 1);
            });
            this.$el.on('mouseout.starrr', function() {
                return _this.syncRating();
            });
            this.$el.on('click.starrr', 'i', function(e) {
                return _this.setRating(_this.$el.find('i').index(e.currentTarget) + 1);
            });
            this.$el.on('starrr:change', this.options.change);
        }

        Starrr.prototype.createStars = function() {
            var _i, _ref, _results;

            _results = [];
            for (_i = 1, _ref = this.options.numStars; 1 <= _ref ? _i <= _ref : _i >= _ref; 1 <= _ref ? _i++ : _i--) {
                _results.push(this.$el.append("<i class='fa fa-2x fa-star-o'></i>"));
            }
            return _results;
        };

        Starrr.prototype.setRating = function(rating) {
            if (this.options.rating === rating) {
                rating = void 0;
            }
            this.options.rating = rating;
            this.syncRating();
            return this.$el.trigger('starrr:change', rating);
        };

        Starrr.prototype.syncRating = function(rating) {
            var i, _i, _j, _ref;

            rating || (rating = this.options.rating);
            if (rating) {
                for (i = _i = 0, _ref = rating - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
                    this.$el.find('i').eq(i).removeClass('fa-star-o').addClass('fa-star').closest('.starrr').attr('data-rating', rating);
                }
            }
            if (rating && rating < 5) {
                for (i = _j = rating; rating <= 4 ? _j <= 4 : _j >= 4; i = rating <= 4 ? ++_j : --_j) {
                    this.$el.find('i').eq(i).removeClass('fa-star').addClass('fa-star-o').closest('.starrr').attr('data-rating', rating);
                }
            }
            if (!rating) {
                return this.$el.find('i').removeClass('fa-star').addClass('fa-star-o').closest('.starrr').attr('data-rating', rating);
            }
        };

        return Starrr;

    })();
    return $.fn.extend({
        starrr: function() {
            var args, option;

            option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            return this.each(function() {
                var data;

                data = $(this).data('star-rating');
                if (!data) {
                    $(this).data('star-rating', (data = new Starrr($(this), option)));
                }
                if (typeof option === 'string') {
                    return data[option].apply(data, args);
                }
            });
        }
    });
})(window.jQuery, window);

$(function() {
    return $(".starrr").starrr();
});




// order_pay_auth_logged - If we have a Stripe Customer ID for the account we process the pre-auth using this method.
$(document).on('submit', "#order-feedback", function (e)
{
	e.preventDefault();
	loadingStart();

	var form = $(this); 
	var correctness_rating = $(this).find('#correctness_rating').attr('data-rating');
	var timing_rating = $(this).find('#timing_rating').attr('data-rating');
	var driver_rating = $(this).find('#driver_rating').attr('data-rating');



	$.ajax({
		type: "POST",
		url: './order/submitOrderFeedback',
		data: form.serialize() + "&action=order_feedback&correctness_rating=" + correctness_rating + "&timing_rating=" + timing_rating + "&driver_rating=" + driver_rating,
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
				$("#order-flow").fadeOut(200);
				$("#order-flow").load(location.href + " #order-flow>*","", function()
				{
					$("#order-flow").fadeIn(200);
					loadingEnd();
				});

				$('html, body').animate({
					scrollTop: $("#order-flow").offset().top
				}, 500);
			}  
		}, error: function (data)
		{
			generateError(data);
		}
	});

	return false; 
});



	// global component
	$(document).on('click', 'ul.order-processing-tab-menu li', function (e) {
		$('ul.order-processing-tab-menu li').removeClass('selected');
		$(this).addClass('selected');
		$('.order-processing-tab').removeClass('selected');
		$('.' + $(this).attr('data')).addClass('selected');
	});


	if($('pre.order-summary').length != 0) {
		text = $('pre.order-summary').text();
		var formattedString = text.split(",").join(",\n");
		$('pre.order-summary').text(formattedString);
	}





});