<h2 class="align-center">Have a coupon?</h2>
<form id="order_promo">
    <div class="select_area push-bottom-40">
        <div class="select_box" id="competition_code">
            <div class="select_box_checkbox"></div>
            <div class="select_box_title">Competition Code</div>
            <div class="select_box_content">
                <p>If you have a competition code, enter it here.</p>
                <input type="textbox" class="textbox" id="promo_data" placeholder="Compeition Code..." />
            </div>
        </div>
        <div class="select_box" id="coupon_redeem">
            <div class="select_box_checkbox"></div>
            <div class="select_box_title">Coupon</div>
            <div class="select_box_content">
                <p>If you have a promo code, enter it here.</p>
                <input type="textbox" class="textbox" id="promo_data" placeholder="Promo Code..." />
            </div>
        </div>
        <div class="select_box" id="dashcash_redeem">
            <div class="select_box_checkbox"></div>
            <div class="select_box_title">DashCash</div>
            <div class="select_box_content">
                <p>Your DashCash balance: <strong>{{Auth::user()->dashcash_balance}}</strong></p>
                <input type="textbox" class="textbox" id="promo_data" placeholder="0.00" maxlength="5" />
            </div>
        </div>
        <div class="select_box selected" id="none">
            <div class="select_box_title">I do not have a coupon.</div>
        </div>
    </div>
    <div class="align-center">
        <button type="submit">Next Step</button>
    </div>
</form>
