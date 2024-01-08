/*
 * global.js
 * Jessie Sanford
 */

function reloadScripts()
{
    var head = document.getElementsByTagName('head')[0];
    var appScript = head.getElementById('js-app');
	head.removeChild(appScript);
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = './js/app.js';
    script.id = 'js-app';
    head.appendChild(script);
}

function handleError(resp) {
    var w = window.open('about:blank', 'windowname');
    w.document.write(resp.responseText);
    w.document.close();
};

function popup(text)
{
	if ( $('.popup').is(':visible') )
	{
		$('.popup').hide();
	}
	$('.popup span').html(text);
	$('.popup').fadeIn(200);

	setTimeout(function(){
		$('.popup').fadeOut(200);
	}, 4000);
}

function generateError(data) {
	console.log(data);
	var w = window.open('about:blank', 'windowname');
	w.document.write(data.responseText);
	w.document.close();
}

function loadingStart() {
    $('#loading').show();
}

function loadingEnd() {
	$('#loading').fadeOut(200);
}









function model_create(content, frozen, classes)
{
	if ( $('#model').is(':visible') )
	{
		$('#model').removeClass('fade_in');
	}
	$('#model').html(content);
	$('#model').addClass(classes);
	$('#model').css("top", 0.5 * $(window).height() - ($('#model').height() / 2));
	$('#mask, #model').addClass('fade_in');
	if ( frozen == true)
	{
		$('#model').addClass('frozen');
	}
}

function model_destroy()
{
	$('#model, #mask').removeClass('fade_in');
}

function model_repos()
{
	$('#model').css("top", 0.5 * $(window).height() - ($('#model').height() / 2));
}


$(document).ready(function() {

	// this should be implemented better
    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

	showLoad = true;

	/*
		Right, so Laravel is fucking stupid and keeps changing the current session csrf token which causes AJAX posts
		to randomly fucking fail because the keys don't match in the back end. The only solution I can seem to find is
		grab the corresponding meta tag for the csrf token by refreshing the page and grabbing that value instead.
	*/

	//$.ajaxPrefilter(function(options) {
	//	if (!options.beforeSend && options.type.toUpperCase() == 'POST') {
	//		options.beforeSend = function (xhr) {
	//			getCSRF_ss(function(token) {
	//				xhr.setRequestHeader('X-CSRF-TOKEN', token);
	//			});
	//		}
	//	}
	//});

	function getCSRF(callback) {
		$.get(location.href, function(response) {
			var tempDom = $('<div>').append($.parseHTML(response));
			var token = $('meta[name="csrf-token"]', tempDom).attr('content');

			$.ajaxSetup({
				headers: {'X-CSRF-TOKEN': token}
			});
			callback(token);
		});
	}

	function getCSRF_ss(callback) {
		$.get('/refresh-csrf', function( data ) {
			callback(data);
		});
	}


	$.ajaxSetup({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
	});

	//$(document).ajaxError(function(event, request, settings) {
	//	getCSRF(function(token) {
	//		settings.headers['X-CSRF-TOKEN'] = token;
	//		//settings.data['_token'] = token;
	//		//$.ajax(settings);
	//	});
    //
	//});


	$(document).ajaxComplete(function(event, request, settings) {
		if (settings.type.toUpperCase() == 'POST') {
			getCSRF_ss(function(token) {
				if (token != $('meta[name="csrf-token"]').attr('content')) {
					$('meta[name="csrf-token"]').attr('content', token);
					$.ajaxSetup({
						headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
					});
				}
			});
		}
	});


	//setInterval(function(){
	//	showLoad = false;
	//	getCSRF_ss(function(token) {
	//		console.log('TOKEN: ' + token);
	//	});
	//}, 3000);


	// $(document).ajaxStart(function () {
	// 	loadingStart();
	// });
	$(document).ajaxStop(function () {
		loadingEnd();
	});



	$(document).on('click', '.popup', function (e) {
		// if ($(e.target).closest('.popup').length === 0) {
			$('.popup').hide();
		// }
	});


	// MAIN SIDEBAR FUNCTIONALITY (OPEN/CLOSE)
	$(document).click(function (e) {
		if ($(e.target).closest('.toggle-main-sidebar, #main-sidebar').length === 0 && $('#main-sidebar').hasClass('visible')) {
			toggleMainSidebar(e);
		}
	});

	$('.toggle-main-sidebar').on('touchstart click', function (e) {
		toggleMainSidebar(e);
	});

	function toggleMainSidebar(e) {
		e.preventDefault();
		var slideview = $('#main-sidebar');

		// slideview is not visible, make visible and add visible class
		if (!slideview.hasClass('visible')) {
			slideview.addClass('visible');
			slideview.addClass('animating left');
		}
		// slideview is visible, hide and remove visible class
		else {
			slideview.removeClass('visible');
			slideview.removeClass('animating left');
		}

	}

// ADMIN SIDEBAR FUNCTIONALITY (OPEN/CLOSE)
	$(document).click(function (e) {
		if ($(e.target).closest('.toggle-admin-sidebar, #admin-sidebar').length === 0 && $('#admin-sidebar').hasClass('visible')) {
			toggleAdminSidebar(e);
		}
	});

	$('.toggle-admin-sidebar').on('touchstart click', function (e) {
		toggleAdminSidebar(e);
	});

	function toggleAdminSidebar(e) {
		e.preventDefault();
		var slideview = $('#admin-sidebar');

		// slideview is not visible, make visible and add visible class
		if (!slideview.hasClass('visible')) {
			slideview.addClass('visible');
			slideview.addClass('animating left');
		}
		// slideview is visible, hide and remove visible class
		else {
			slideview.removeClass('visible');
			slideview.removeClass('animating left');
		}

	};


	$(document).on('submit', '#contact_form', function () {
		form = $(this);

		$.ajax({
			type: "POST",
			url: "controllers/misc.php",
			data: form.serialize() + "&action=submit_contact",
			dataType: "json",
			success: function (data) {
				$('#contact_form input[type=submit]').prop('disabled', true);
				if (data.form_check == 'error') {
					$('#contact_form input[type=submit]').prop('disabled', false);
					popup(data.alert);
				}
				else {
					popup(data.alert);
					$('#contact_form')[0].reset();
					$(this).children('input[type=submit]').prop('disabled', true);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				popup(xhr.status + thrownError);
			}
		});
		return false;
	});

// global component
	$(document).on('click', 'ul.tab-menu li', function (e) {
		$('ul.tab-menu li').removeClass('selected');
		$(this).addClass('selected');
		$('.tab-panel').removeClass('tab-current');
		$('#' + $(this).attr('data')).addClass('tab-current');
	});




	$(document).on('submit', '#forgot_password', function (e) {
		e.preventDefault();

		var form_data = $(this).serialize();

		$.ajax({
			type: "POST",
			url: "./controllers/user.php",
			data: form_data + "&action=user_forgot_password",
			dataType: 'json',
			success: function (data) {
				if (data.form_check == 'error') {
					console.log(data);
					popup(data.alert);
					$('input[name=' + data.error_source + ']').addClass('form_error');
				}
				else {
					popup(data.alert);
					// window.location.href = './user?action=login';
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.responseText + thrownError);
			}
		});

		return false;
	});


// registration scripts ---------------------------------------------

	$('#phone').keydown(function (e) {
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				// Allow: Ctrl+A
			(e.keyCode == 65 && e.ctrlKey === true) ||
				// Allow: Ctrl+C
			(e.keyCode == 67 && e.ctrlKey === true) ||
				// Allow: Ctrl+X
			(e.keyCode == 88 && e.ctrlKey === true) ||
				// Allow: home, end, left, right
			(e.keyCode >= 35 && e.keyCode <= 39)) {
			// let it happen, don't do anything
			return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}

		var foo = $(this).val().split("-").join(""); // remove hyphens
		foo = foo.match(new RegExp('.{1,4}$|.{1,3}', 'g')).join("-");
		$(this).val(foo);
	});


	$('#register_form').submit(function (e) {
		e.preventDefault();

		$('.form_error').removeClass('form_error');
		form_data = $(this).serialize();

		$.ajax({
			type: "POST",
			url: "./user/register",
			data: form_data,
			dataType: 'json',
			success: function (data) {
				if (data.errors != undefined) {
					popup(data.errors[Object.keys(data.errors)[0]]);
					for (key in data.errors) {
						$('#' + key).addClass('form_error');
					}
				}
				else {
					ga('send', 'event', 'functions', 'register', 'register', '1');
					$('.form_error').removeClass('form_error');
					$('#register_form')[0].reset();
					popup(data.alert);
					setTimeout(function () {
						window.location.href = './user/verify';
					}, 1000);
				}
			},
			error: function (data) {
				generateError(data);
			}
		});
		return false;
	});

	$('#verif_form').submit(function (e) {
		e.preventDefault();

		$.post('./controllers/user.php', $('#verif_form').serialize() + "&action=user_verify",
			function (data) {
				if (data.form_check == 'error') {
					popup(data.alert);
					for (key in data.error_source) {
						$('#' + data.error_source).addClass('form_error');
					}
				}
				else {
					$('.form_error').removeClass('form_error');
					$('#verif_form')[0].reset();
					popup(data.alert);
					window.location.href = './';
				}

			}, 'json');
		return false;
	});

	$(document).on('click', '#resend_verif_code', function (e) {
		e.preventDefault();

		$.post('./controllers/user.php', "action=user_resend_verif",
			function (data) {
				popup(data.alert);
			}, 'json');

		return false;
	});

	$(document).on('click', '.form_error, .form-error', function (e) {
		$(this).removeClass('form_error form-error');
	});

	$(document).on('submit', 'form', function (e) {
		$('form_error').removeClass('form_error');
	});


	$(document).on('click', "#user_logout", function (e) {
		e.preventDefault();

		$.ajax({
			type: "POST",
			url: "/user/logout",
			dataType: 'json',
			success: function (data) {
				popup(data.alert);
				window.location.href = "/";
			},
			error: function (data) {
				generateError(data);
			}
		});

		return false;
	});


// driver app js submit
	$(document).on('submit', '#driver_app', function (e) {
		e.preventDefault();

		$.post('./controllers/misc.php', $('#driver_app').serialize() + '&action=submit_driver_app',
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


	$(document).on('click', '.confirm-action', function (e) {
		e.preventDefault();

		var desc = $(this).attr('data-desc');
		var value = $(this).attr('data-value');
		var action = $(this).attr('data-action');
		var buttonText = $(this).attr('data-button');

		var content = '<div class="push-bottom-10">' + $(this).attr('data-desc') + '</div>';

		var modal = new Modal('Confirm Action', null);
		modal.setContent(content);
		modal.submitButton.innerText = buttonText;
		modal.submitButton.className = modal.submitButton.className + " " + action;
		modal.submitButton.dataset.value = value;
		modal.setVisible();

		modal.setSubmitAction(function() {
			this.destroy();
		});

	});

	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		$("input.textbox, textarea").click(function () {
			element = $(this);
			$('html, body').animate({
				scrollTop: element.offset().top - 40
			}, 400);
		});
	}

	// handle header login btn click
	$(document).on('click', '#header-login-btn', function(e) {
		var modal = new Modal('Login');
        var changeEmailForm = '<login-form></login-form>';
        modal.setVue(changeEmailForm);
		modal.setVisible(true);
        modal.toolbarEl.remove();

		document.addEventListener('logged-in', function(e) {
			modal.destroy();
		});
	});

	// handle login form submit
    $(document).on('submit', '#login_form', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

		$(this).hide();

		var loading = '<span id="loading5"> <span id="outerCircle"></span></span>';
		$(this).parent().append(loading);

        var req = $.ajax({
            type: "POST",
            url: "./user/login",
            data: formData,
            dataType: "json",
            context: this,
            success: function (resp) {
                if (resp.formCheck !== 'error') {
                    ga('send', 'event', 'functions', 'login', 'login', '1'); // google analytics

                    $("#header").load(location.href + " #header", function() {
                        document.dispatchEvent(new Event('logged-in'));
					});
                }
                else {
                	$('#loading5').remove();
                	$(this).show();
                	$(this).find('.login-error').text('Invalid login, please try again.').fadeIn(200);
				}
            },
            error: function (data) {
				handleError(data);
            }
        });

        return false;
    });


});


// creates a modal component

function Modal(title, content) {
    this.title = title;
    this.content = content;

    // parseDOM should be a globally access thing
    this.parseDOM();
    this.create();
};

Modal.prototype.parseDOM = function() {
    this.body =  jQuery('body');
};

Modal.prototype.create = function()
{
    this.maskEl = document.createElement('div');
    this.maskEl.id = 'modal-mask';

    this.containerEl = document.createElement('div');
    this.containerEl.className = 'modal-container';

    this.titleEl = document.createElement('div');
    this.titleEl.className = 'modal-title';
    this.titleEl.innerHTML = this.title;

    this.contentEl = document.createElement('div');
    this.contentEl.className = 'modal-content';

    this.containerEl.addEventListener('keypress', function (e) {
        var key = e.which || e.keyCode;
        if (key === 13 && this.submitAction) { // enter key
            this.executeSubmit();
        }
    }.bind(this));

    this.toolbarEl = document.createElement('div');
    this.toolbarEl.className = 'modal-toolbar';

    this.submitButton = document.createElement('button');
    this.submitButton.className = 'btn push-right';
    this.submitButton.innerText = 'OK';

    this.cancelButton = document.createElement('button');
    this.cancelButton.className = 'btn btn-grey';
    this.cancelButton.innerText = 'Cancel';
    this.setCancelAction();

    this.toolbarEl.appendChild(this.submitButton);
    this.toolbarEl.appendChild(this.cancelButton);

    jQuery(this.containerEl).append(this.titleEl);
    jQuery(this.containerEl).append(this.contentEl);
    jQuery(this.containerEl).append(this.toolbarEl);

    // add dynamically created DOM elements to body
    this.body.prepend(this.containerEl);
    this.body.prepend(this.maskEl);

    this.resize();

    this.maskEl.addEventListener('click', function(e) {
        this.destroy();
    }.bind(this));
};

Modal.prototype.resize = function() {
    jQuery(this.containerEl).css("top", 0.5 * $(window).height() - (jQuery(this.containerEl).height() / 2));
};

Modal.prototype.setVisible = function() {
    jQuery(this.maskEl).fadeIn(100);
    jQuery(this.containerEl).fadeIn(100);
};

Modal.prototype.destroy = function() {
    this.maskEl.remove();
    this.containerEl.remove();
};

Modal.prototype.setTitle = function(title) {
    this.title = title;
    this.titleEl.innerHTML = title;
};

Modal.prototype.setVue = function(shorthand) {
    this.content = shorthand;
    this.contentEl.innerHTML = shorthand;

    const content = new Vue({
        el: '.modal-content',
        data: {
            message: 'message here'
        }
    });
    this.resize();
};

/*
* For plain text or html
* */
Modal.prototype.setContent = function(content) {
    this.content = content;
    this.contentEl.innerHTML = content;
    this.resize();
};

Modal.prototype.setSubmitAction = function(action) {
    this.submitAction = action;
    this.submitButton.addEventListener('click', function(e) {
        this.executeSubmit();
    }.bind(this));
};

Modal.prototype.executeSubmit = function() {
    this.submitAction();
};

Modal.prototype.setCancelAction = function() {
    this.cancelButton.addEventListener('click', function(e) {
        this.destroy();
    }.bind(this));
};

Modal.prototype.setData = function(data) {
    this.data = data;
};

Modal.prototype.getElement = function(data) {
    return this.containerEl;
};



// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
window.geolocate_ = function(autocomplete) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
};

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}

window.initAutocomplete = function() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.

    if (document.getElementById('address-street')) {
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('address-street')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
        geolocate();
    }
};

//# sourceMappingURL=utils.js.map
