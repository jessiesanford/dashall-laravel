<div class="order_row align_center push-bottom-40" id="{{$order['order_id']}}">
    <div class="manage_order_heading heading flex-row">
        <div class="title">#{{$order->order_id}}</div>
        <div class="cell-right order-time">{{ date("M d Y - g:i a", strtotime($order['init_time'])) }}</div>
    </div>
    <div class="driver_order_status" id="ds_{{$order['status_id']}}" data-order_id="{{$order['order_id']}}">{{$order->status['name']}}</div>
    @if ($order['status'] == 'ARR')
        <button class="mark_complete push-top button_lrg" data-order_id="{{$order['order_id']}}"><i class="fa fa-check-circle"></i>&nbsp; Mark As Complete</button>
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
            <button class="update_cost" data-order_id="{{$order['order_id']}}">Update Cost</button>
        </div>
    @elseif ($order->status['rank'] == 3)
        <button class="button button_lrg push-top-20 send_arrival_status"><i class="fa fa-bell"></i>&nbsp; I have arrived!</button>
    @endif

    <div class="order_info push-top-20">
        <div class="title">Delivery Address</div>
        <div class="order_address"><a target="_blank" href="https://www.google.ca/maps/search/' . str_replace(' ', '%20', $order['street']) . '">{{$order->address['street']}}</a></div>
    </div>

    <button class="self_assign button_green button_lrg wid-50 push-top-20" data-order_id="{{$order['order_id']}}"><i class="fa fa-car"></i>&nbsp; Assign Me</button>
    <br>
    <button class="report_issue_init push-top-20" data-order_id="'. $order['order_id'] .'"><i class="fa fa-exclamation-circle"></i>&nbsp; Report Issue</button>



</div>

