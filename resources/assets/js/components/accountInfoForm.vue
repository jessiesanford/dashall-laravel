<template>
    <div class="tab-panel tab-current" id="tab-personal" >
        <form id="updateSettings" method="POST">

            <div class="flex-row row-top row-collapse">
                <div class="flex-cell wid-25 push-bottom-10">
                    <strong>Avatar</strong>
                </div>
                <div>
                    <div class="flex-row row-collapse row-top">
                        <div class="flex-cell">
                            <label class="block">
                                <!--@if ($user->avatar)-->
                                <img src="" alt="" />
                                <!--@else-->
                                <input type="file" name="pic" accept="image/*">
                                <!--@endif-->
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="flex-row row-top row-collapse">
                <div class="flex-cell wid-25 push-bottom-10">
                    <strong>Contact</strong>
                </div>
                <div>
                    <div class="flex-row row-collapse row-top">
                        <div class="flex-cell push-right-20">
                            <label class="block push-bottom-10">
                                <div>First Name</div>
                                <input type="text" class="textbox" v-model="user.first_name" name="first_name" />
                            </label>
                        </div>
                        <div class="flex-cell">
                            <label class="block">
                                <div>Last Name</div>
                                <input type="text" class="textbox" v-model="user.last_name" name="last_name" />
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="flex-row row-top row-collapse">
                <div class="flex-cell wid-25 push-bottom-10">
                    <strong>Email Address</strong>
                </div>
                <div>
                    <div class="flex-row row-collapse row-top">
                        <div class="flex-cell push-right-20">
                            <label class="block push-bottom">
                                {{user.email}}<br>
                                (<a href="#" id="initChangeEmailAddress">Change email address</a>)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="flex-row row-top row-collapse">
                <div class="flex-cell wid-25 push-bottom-10">
                    <strong>Phone Number</strong>
                </div>
                <div>
                    <div class="flex-row row-collapse row-top">
                        <div class="flex-cell push-right-20">
                            <label class="block push-bottom">
                                +{{user.phone}} <br>(<a href="#" id="initChangePhoneNumber">Change phone number</a>)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="flex-row row-top row-collapse">
                <div class="flex-cell wid-25 push-bottom-10">
                    <strong>Address</strong>
                </div>
                <div>
                    <div class="flex-row row-collapse row-top">
                        <div class="flex-cell push-right-20">
                            <div id="address-formatted" v-if="userAddress">
                                {{userAddress.street}}<br>
                                {{userAddress.city}}, {{userAddress.province}}<br>
                                {{userAddress.postal_code}}<br>
                            </div>
                            <label id="address-input-label" class="block push-bottom-10 userAddress ? 'hidden' : ''">
                            <div>Street</div>
                            <input type="textbox" id="account-address" class="textbox" name="address" value="" />
                            </label>
                            (<a href="" id="init-change-address">Change Address</a>)
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="align_center">
                <button type="submit">Update Info</button>
                <button type="reset">Cancel</button>
            </div>
        </form>

    </div>



</template>

<script>
    export default {
        props: ['user', 'userAddress'],
        data() {
            return {
                swag: this.user.first_name
            }
        },
        mounted() {
            var addressChange = false;
            var placeLocation = {};
            var locationData = {};

            if ((document.getElementById('account-address'))) {

                var addressAutocomplete = new google.maps.places.Autocomplete(
                    /** @type {!HTMLInputElement} */(document.getElementById('account-address')),
                    {types: ['geocode']});
                var locationFormat = {
                    street_number: 'short_name',
                    route: 'long_name',
                    locality: 'long_name',
                    administrative_area_level_1: 'short_name',
                    country: 'long_name',
                    postal_code: 'short_name'
                };

                addressAutocomplete.addListener('place_changed', function () {
                    placeLocation = fillInAddress_(addressAutocomplete);

                    Object.keys(placeLocation).forEach(function (key) {
                        if (locationFormat[key]) {
                            var val = placeLocation[key];
                            var val2 = placeLocation[key][locationFormat[key]];
                            locationData[key] = val2;
                        }
                    });

                    addressChange = true;
                    document.getElementById('account-address').setAttribute('disabled', 'disabled');
                });
                geolocate_(addressAutocomplete);
            }

             document.getElementById('init-change-address').addEventListener('click', function(e) {
                e.preventDefault();
                var formattedAddressEl = document.getElementById('address-formatted');
                formattedAddressEl.style.display = 'none';
                document.getElementById('address-input-label').classList.remove('hidden');
                addressChange = true;
            });


            $(document).on('submit', '#updateSettings', function(e)
            {
                e.preventDefault();

                if (addressChange && Object.keys(placeLocation).length === 0) {
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
                                $("#account-settings-view").load(placeLocation.href + " #account-settings-view>*", "", function () {
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
                                $("#account-settings-view").load(placeLocation.href + " #account-settings-view>*", "", function () {
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
        }
    };
</script>
