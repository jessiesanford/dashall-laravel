<div class="order-row align_center push-bottom-40" data-order_id="{{$order['order_id']}}">

    <div class="manage_order_heading heading flex-row">
        <div class="title">#{{$order->order_id}}</div>
        <div class="cell-right order-time">{{ date("M d Y - g:i a", strtotime($order['init_time'])) }}</div>
    </div>

{{--{{dd($order)}};--}}

    <div class="driver-order-status status_{{$order['status_id']}}" id="processing_{{$order['status_id']}}" data-order_id="{{$order['order_id']}}">{{$order->status['name']}}</div>
    @if ($order['status_id'] == 'ARR')
        <button class="mark-complete push-top btn-lrg" data-order_id="{{$order['order_id']}}"><i class="fa fa-check-circle"></i>&nbsp; Mark As Complete</button>
    @elseif ($order['status'] == 'COM')
        Complete: {{ date("M d - g:i a", strtotime($order->elapsed())) }}
    @endif

    <div class="cell">
        <div class="cell title">Order Description</div>
        <pre class="summary">{{$order['summary']}}</pre>
    </div>

    <div class="cell">
        <div class="title">Order Location</div>
        <div class="location">{{$order['location']}}</div>
    </div>

    @if ($order->status['rank'] == 2)
        <div class="order_cost push-top-20 push-bottom-20">
            <input class="textbox align_center" type="textbox" placeholder="How much did you pay?" />
            <button id="initDriverUpdateCost" data-order_id="{{$order['order_id']}}">Update Cost</button>
        </div>
    @elseif ($order->status['rank'] == 3)
        <button class="button btn-lrg push-top-20 send-arrival-status"><i class="fa fa-bell"></i>&nbsp; I have arrived!</button>
    @endif

    <div class="order_info push-top-20">
        <div class="title">Customer Info</div>
        <div class="order_details">
            {{$order->user['first_name']}} {{$order->user['last_name']}}<br />
            <button class="btn-alt order_phone push-top push-bottom" href="tel:{{$order->user['phone']}}"><i class="fa fa-phone"></i></button>
        </div>
        <div class="title">Delivery Address</div>
        <div class="order_address"><a target="_blank" href="https://www.google.ca/maps/search/' . str_replace(' ', '%20', $order['street']) . '">{{$order->address['street']}}</a></div>
    </div>

    @if ($order->cost['amount'] != 0.00)
        <div class="cell push-top-20">
            <div class="title">Cost of Order</div>
            <div class="order_cost_amount">$<span>{{number_format($order->cost['amount'], 2)}}</span></div>
        </div>
    @endif
</div>

