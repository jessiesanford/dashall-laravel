@extends('layouts.admin')
@section('content')
    <script type="text/javascript" src="./js/admin_settings.js"></script>
    <link rel="stylesheet" href="./css/admin_settings.css" />

    <div id="container">
        <div class="section" id="settings_section">

            <div class="page-heading">
                <h1 class="page-title">{{$title}}</h1>
            </div>

            @if($user)
                <label>
                    <div>Name</div>
                    {{$user['first_name']}} {{$user['last_name']}}
                </label>
                <label>
                    <div>Email</div>
                    {{$user['email']}}
                </label>
                <label>
                    <div>Phone</div>
                    {{$user['phone']}}
                </label>
                <label>
                    <div>Registration Date</div>
                    {{date("M d Y - g:i a", strtotime($user['reg_date']))}}
                </label>

                <br />

                @if ($user['user_group'] < 2)
                    <br />
                    <button id="promote_to_driver" data-user_id={{$user['user_id']}}><i class="fa fa-car"></i>&nbsp; Promote To Driver</button>
				@endif
                @if ($user['user_group'] == 2)
                    <br />
                    <button id="remove_driver" data-user_id={{$user['user_id']}}><i class="fa fa-times"></i> Remove Driver</button>
                @endif

                @if($orders)
                    <hr />
                    <h2>Customer Orders</h2>
                    <div class="push-bottom-20">
                        {{$orders->links()}}
                    </div>
                    <div class="table">
                        <div class="thead row">
                            <div class="cell wid-10">Status</div>
                            <div class="cell wid-20">Desc</div>
                            <div class="cell wid-20">Location</div>
                            <div class="cell wid-10">Cost</div>
                            <div class="cell wid-20">Time</div>
                            <div class="cell wid-10">Address</div>
                        </div>
                    </div>
                    @foreach ($orders as $order)
                        <div class="row">
                            <div class="trow cell wid-10">{{$order['status_id']}}</div>
                            <div class="trow cell wid-20">
                                @if (strlen($order['summary']) > 100)
                                    {{substr($order['summary'], 0, 100) . '...'}}
                                    {{$order['summary']}}
                                @else
                                    {{$order['summary']}}
                                @endif
                            </div>
                            <div class="trow cell wid-20">{{$order['location']}}</div>
                            <div class="trow cell wid-10">${{number_format((($order['amount'] * $order['margin'] + $order['delivery_fee'])), 2)}}</div>
                            <div class="trow cell wid-20">
                                {{date("M d Y - g:i a", strtotime($order['init_time']))}}
                                <br />
                            </div>
                            <div class="trow cell">{{$order->address['street']}}</div>
                        </div>
                    @endforeach
                    <br>
                @endif

                <form id="add_dashcash">
                    <h3>DashCash Balance</h3>
                    <h3>${{$user['dashcash_balance']}}</h3>
                    <input type="text" class="textbox" name="amount" />
                    <input type="hidden" value="{{$user['user_id']}}" name="user_id" />
                    <button type="submit">Add</button>
                </form>
            @endif



        </div>
    </div>

@endsection
