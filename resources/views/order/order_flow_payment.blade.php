<h2 class="align-center">Payment Information</h2>

<div class="flex-row row-collapse row-top">
    <div class="wid-50 order-payment-breakdown">
        <p>
            DashAll accepts and processes VISA, VISA Debit, Mastercard, and American Express.
        </p>
        <div class="">
            <h3>Cost Breakdown</h3>
            <div>Order Cost (Determined at pickup)</div>
            <div>Service Fee (13%)</div>
            <div>Delivery Fee ($7)</div>
        </div>
        <br />
    </div>
    <div class="">
        @if (empty($stripeCustomer))
            <div class="">
                <form action="" method="POST" id="order_pay_auth" class="form_fill">
                    <label class="credit-card-label block push-bottom">
                        <div><i class="fa fa-credit-card"></i>&nbsp; Credit Card Number</div>
                        <input type="text" maxlength="16" autocomplete="off" class="textbox wid-100 card-number" placeholder="Credit Card Number" />
                    </label>
                    <div class="push-bottom-40 row no_border">
                        <label class="push-right-10">
                            <div>MM</div>
                            <input type="text" size="2" maxlength="2" class="textbox card-expiry-month width_auto align_center" placeholder="01" />
                        </label>
                        <label class="push-right-10">
                            <div>YY</div>
                            <input type="text" size="2" maxlength="2" class="textbox card-expiry-year width_auto align_center" placeholder="18" />
                        </label>
                        <label class="">
                            <div><i class="fa fa-lock"></i>&nbsp; CVC</div>
                            <input type="text" size="4" maxlength="3" autocomplete="off" class="textbox card-cvc align_center width_auto" placeholder="***" />
                        </label>
                    </div>
                    <button type="submit" class="button button_lrg resp_expand">Authorize Card</button>
                </form>
            </div>
        @else
            <div class=""></div>
            <div class="">
                <h3>Would you like to use the following credit card?</h3>
                <form action="" method="POST" id="order_pay_auth_logged">
                    <div class="credit-card-saved flex-row">
                        <div>
                            <i class="fa fa-cc-{{strtolower($stripeCustomer->brand)}}"></i>&nbsp; {{$stripeCustomer->brand}} **** **** **** {{$stripeCustomer->last4}}<br />
                            <div>Expires {{$stripeCustomer->exp_month}} / {{$stripeCustomer->exp_year}}</div>
                        </div>
                        <div class="cell-right">
                            <button class="confirm_action remove-credit-card-btn btn-alt"
                                    data-action="delete_credit_card"
                                    data-button="Confirm"
                                    data-desc="Are you sure you want to remove this card from your account?"
                                    ><i class="fa fa-times"></i></button>
                        </div>

                    </div>
                    <button type="submit" class="button_lrg resp_expand push-bottom">Authorize Card</button>
                </form>
            </div>
        @endif
    </div>
</div>


