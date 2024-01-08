<script id="order_row" type="text/html">
    <div class="order-log-row">
        <div class="flex-row order-info-top" data="@{{order_id}}">

            <div class="flex-cell wid-10">@{{order_id}}</div>

            <div class="flex-cell wid-10">
                <span class="status_@{{status_id}} order_status">@{{status_id}}</span>
            </div>

            <div class="flex-cell wid-10">
                @{{location}}
            </div>

            <div class="flex-cell wid-20">
                @{{init_time}}
            </div>
            <div class="order-customer flex-cell wid-20">
                <a href="./admin/customer/@{{user.user_id}}">@{{user.first_name}} @{{user.last_name}}</a>
            </div>
            <div class="order-address flex-cell wid-20">
                @{{address.street}}
            </div>
        </div>
        <div class="order-info-bottom">
            <div class="flex-row no_border tcat">
                <div class="flex-cell wid-30">Order Desc</div>
                <div class="cell cell-right align-right wid-20">Complete Time</div>
            </div>
            <div class="flex-row row-alt">
                <div class="flex-cell wid-30">
                    @{{summary}}
                </div>
                <div class="cell cell-right align-right wid-20">
                    @{{time_transpired}}
                </div>
            </div>
        </div>
    </div>
</script>