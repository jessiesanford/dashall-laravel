<div class="manage-order-container">
    <div class="manage-order push-bottom-40" id="{{$order->order_id}}">

        <div class="manage-order-heading flex-row row-collapse">
            <div class="order-id">
                <span class="status_{{$order->status_id}} status-label"><strong>#{{$order->order_id}} - {{$order->status['name']}}</strong></span>
            </div>
            <div class="cell-right order-time">{{ $order->calcHumanTime() }} ({{ date("M d Y - g:i a", strtotime($order['init_time'])) }})</div>
        </div>

        <div>
            @if ($order->driver_id == null && $order->status_id == 'APP')
                <div class="notice">ASSIGN A DRIVER</div>
            @endif
        </div>

        <div class="manage-order-toggle">
            <div class="order-toolbar flex-row">
                <div>
                    <button class="edit-order"><i class="fa fa-pencil push-right"></i> <span>Edit</span></button>
                </div>
                <div class="cell-right">
                    <button class="confirm-action" data-desc="Are you sure you want to delete this order?" data-button="Delete Order" data-action="delete-order" data-value="{{$order->order_id}}"><i class="fa fa-times"></i></button>
                    <button class="init-update-order-status" data-order_id="{{$order->order_id}}"><i class="fa fa-flag"></i><span></span></button>
                </div>
            </div>
            <div class="manage-order-info">
                <div class="flex-row row-top row-collapse">
                    <div class="cell wid-50">
                        <form class="manage-order-form">
                            {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                            {{csrf_field()}}
                            <div class="title flex-row">
                                <i class="fa fa-lg fa-wid-20 fa-shopping-cart push-right-10"></i> Order Pickup
                            </div>
                            <div class="location cell form-input" data-value="location">{{$order->location}}</div>

                            <div class="title flex-row">
                                <i class="fa fa-lg fa-wid-20 fa-list-alt push-right-10"></i> Order Summary
                            </div>
                            <div class="order-summary cell form-input" data-value="summary"><pre class="order-summary">{{$order->summary}}</pre></div>

                            <div class="title flex-row">
                                <i class="fa fa-lg fa-wid-20 fa-truck push-right-10"></i> Order Dropoff
                            </div>
                            <div class="street cell form-input" data-value="address">{{$order->address['street']}}</div>

                            <div class="title"><i class="fa fa-lg fa-wid-20 fa-user push-right-10"></i> Customer Info</div>
                            <div class="info info-customer cell">
                                <div class="flex-row">
                                    <div>
                                        <a class="customer_name" href="admin?module=customer?id={{$order->user->user_id}}'">{{$order->user->first_name}} {{$order->user->last_name}}</a><br />
                                    </div>
                                    <div class="cell-right">
                                        <button class="btn-alt call_phone"  onclick="window.open('tel:{{$order->phone}}}}');"><i class="fa fa-phone"></i></button>
                                        <button class="btn-alt order_text" data-phone="{{$order->user['first_name']}}"><i class="fa fa-envelope"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cell wid-50">
                        <form class="manage-order-costs">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="title flex-row">
                                <div class="">Payment Info</div>
                                <div class="cell-right"><button class="float-right edit-order-cost btn-sml"><i class="fa fa-pencil"></i></button></div>
                            </div>
                            <div class="cell">
                                <div class="cost_margin">Margin: %<span>{{$order->cost['margin'] * 100 - 100}}</span></div>
                                <div class="cost_delivery_fee">Delivery Fee: $<span>{{$order->cost['delivery_fee']}}</span></div>
                                <div class="cost_discount">Discount: $<span>{{$order->cost['discount_amount']}}</span></div>
                                <div class="cost_tip">Driver Tip: $<span>{{$order->cost['tip']}}</span></div>
                                @if ($order['promo'])
                                    <div class="cost_promo">Promo: <span>{{$order['promo']}}</span></div>
                                @endif
                                <div class="cost_total">Total: $<span>{{ $order->totalAmount() }}</span></div>
                                @if ($order['status'] == 'COM')
                                    <button class="collect-payment">Collect</button>
                                @endif
                            </div>
                        </form>
                        <div class="title">Driver Management</div>
                        <div class="cell">
                            @if ($order->status['rank'] < 0)
                                A driver cannot be assigned yet.
                            @elseif (!$order['driver_id'])
                                <select class="select_driver">
                                    @foreach ($drivers as $driver)
                                        <option value="{{$driver->user_id}}">{{$driver->user->first_name}} {{$driver->user->last_name}}</option>
                                    @endforeach
                                </select>
                                <button class="assign-driver" data-order_id="{{$order['order_id']}}'">Assign Driver</button>
                            @else
                                {{$order->driver->user['first_name']}} {{$order->driver->user['last_name']}}
                                @if ($order->status['rank'] <= 5)
                                    (<a class="unassign-driver" href="#">Unassign</a>)<br />

                                @endif
                            @endif

                            @if ($order['status'] == "ARR")
                                <button class="push-top confirm-action" data-desc="Mark order #{{$order['order_id']}} as complete?" data-button="Mark Complete" data-value="{{$order['order_id']}}" data-action="mark_complete">
                                    <i class="fa fa-check-circle"></i>&nbsp; Mark As Complete
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>