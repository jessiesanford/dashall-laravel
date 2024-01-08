<script id="transaction_row" type="text/html">
    <div class="trans_row">
        <div class="row trans_summary" data="@{{order_id}}">
            <div class="cell wid-10">@{{order_id}}</div>
            <div class="trans_date cell wid-25" data="">@{{init_time}}</div>
            <div class="trans_user cell wid-25" data-repeat_customer="">
                @{{#repeat_customer}}
                <i class="fa fa-refresh push_right"></i>
                @{{/repeat_customer}}
                <a href="admin/customer/@{{user.user_id}}">@{{user.first_name}} @{{user.last_name}}</a>
            </div>
            <div class="trans_promo cell wid-15" data="">@{{promo}}</div>
            <div class="trans_amount cell cell-right align-right wid-20 strong" data="">@{{profit}}</div>
        </div>
        <div class="trans_info">
            <div class="row no_border tcat">
                <div class="cell wid-20">Order Amount</div>
                <div class="cell wid-20">Delivery Fee</div>
                <div class="cell wid-20">Tip</div>
                <div class="cell wid-20">Discount</div>
                <div class="cell wid-20">Strip Cut</div>
                <div class="cell cell-right align-right wid-20">Revenue</div>
            </div>
            <div class="row row-alt">
                <div class="cell wid-20">@{{cost.amount}}</div>
                <div class="cell wid-20">@{{cost.delivery_fee}}</div>
                <div class="cell wid-20">@{{cost.tip}}</div>
                <div class="cell wid-20">@{{cost.discount_amount}}</div>
                <div class="cell wid-20">@{{stripe_cut}}</div>
                <div class="cell cell-right align-right wid-20">@{{revenue}}</div>
            </div>
        </div>
    </div>
</script>