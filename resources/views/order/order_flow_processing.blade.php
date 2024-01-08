<h2 class="no-padd align-center">Order Overview</h2>

<ul class="order-processing-tab-menu">
    <li class="selected" data="processing-status"><i class="fa fa-send"></i>&nbsp; <span>Status</span></li>
    <li data="processing-details"><i class="fa fa-cutlery"></i>&nbsp; <span>Details</span></li>
</ul>

<hr />

<div class="order-processing row-collapse row-top">
    <div class="processing-details order-processing-tab">
        <div class="details-pickup-location push-bottom-10">
            <h3 class="push-bottom-10 no-padd"><i class="fa fa-lg fa-wid-20 fa-shopping-cart push-right-10"></i> Pickup Location</h3>
            {{$order->location}}
        </div>
        <div class="details-order-summary push-bottom-10">
            <h3 class="push-bottom-10 no-padd"><i class="fa fa-lg fa-wid-20 fa-list-alt push-right-10"></i> Order Description</h3>
            <pre class="order-summary">{{$order->summary}}</pre>
        </div>
        <div class="details-order-cost push-bottom-10">
            <h3 class="push-bottom-10 no-padd"><i class="fa fa-lg fa-wid-20 fa-money push-right-10"></i> Order Cost</h3>
            @if ($order->cost->amount == 0.00)
                Order cost not determined yet.
            @else
                <div class="flex-row">
                    <div class="wid-50">Order Cost:</div>
                    <div>${{$order->cost->amount}}</div>
                </div>
                <div class="flex-row">
                    <div class="wid-50">Delivery Fee:</div>
                    <div>${{$order->cost->delivery_fee}}</div>
                </div>
            @endif
        </div>
        <div class="details-dropoff-location">
            <h3 class="push-bottom-10 no-padd"><i class="fa fa-lg fa-wid-20 fa-truck push-right-10"></i> Dropoff Location</h3>
            {{$order->address->street}}<br />
            {{$order->address->city}}<br />
            {{$order->address->province}}
        </div>


        @if (!empty($order->promo()->first()))
            <hr />
            <h2>Order Promotions</h2>
            {{$order->promo}}<br />
            {{$order->promo()->first()->promo_desc}}
        @endif
    </div>
    <div class="order-processing-tab selected">
        <h3 class="no-padd push-bottom-10">Order Status</h3>
            <div class="order-status_resp summary push-bottom-20">{{$order->status['summary']}}</div>

            <div class="processing-status">
                @foreach($processingStatusList as $pStatus)
                    <div id="processing_{{$pStatus['status_id']}}"
                         class="status_{{$pStatus['status_id']}} processing-order-status {{($order->status_id == $pStatus['status_id'])?'active-status':''}} {{($order->status['rank'] > $pStatus['rank'])?'finished-status':'queued-status'}}">
                        {{$pStatus->name}}
                    </div>
                @endforeach
            </div>

            @if ($order->status['rank'] >= 2)
                <div class="flex-row push-top-40">
                    <div class="">Your delivery driver is {{$order->user['first_name']}}!</div>
                    <div class="cell-right"><a class="button" href="tel:{{$order->user['phone']}}"><i class="fa fa-phone-square"></i></a></div>
                </div>
            @endif
    </div>
</div>




