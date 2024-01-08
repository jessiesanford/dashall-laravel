<div id="order_area_wrap" class="wrap">
    <div id="order_area" class="padd_y_40">
        @if ( $order->status_id == "AWD_S1" )
            @include('order.order_flow_details')
        @elseif ( $order->status_id == "AWD_S2" )
            @include('order.order_flow_address')
        @elseif ($order->status_id == "AWD_S2")
            @include('order.order_flow_promo')
        @elseif ($order->status_id == "AWD_S3" || $order->cost()->first()->pay_auth == 0)
            @include('order.order_flow_payment')
        @elseif ($order->status['rank'] >= 0 && $order->status_id != 'COM')
            @include('order.order_flow_processing')
        @elseif ($order->status_id == 'COM')
            @include('order.order_flow_processing_payment')
        @else
            Something is wrong with your order.
        @endif
        <hr>
        @if ($order->status['rank'] == -1 && $order['status_id'] == 'DEN')
            <button id="deactivate_order" class="push-top-20">Start Another Order</button>

        @elseif ($order->status['rank'] <= 1)
            <div class="flex-row">
                @if ($order->status['rank'] == -1)
                    <div class=""><a href="#" class="order-step-back">Back</a></div>
                @endif
                <div class="cell-right"><a href="#" class="cancel-order-init">Cancel Order</a></div>
            </div>

        @endif

    </div>
</div>