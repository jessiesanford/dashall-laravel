@extends('layouts.admin')
@section('content')
    @include('mustache.mus_transaction')
    <script type="text/javascript" src="./js/payroll.js"></script>

    <div id="container">
        <div class="section" id="settings_section">

            <div class="page-heading">
                <h1 class="page-title">{{$title}}</h1>
            </div>

            <div class="push-bottom-20">{{ $payroll->links() }}</div>

            <div id="trans_sidebar">
                <div id="trans_sidebar_content">
                    <div class="row thead">
                        <div class="cell">Cash Out</div>
                    </div>
                    <div class="row">
                        <div class="cell xlrg_text"><strong>$<span id="cashout_value">0.00</span></strong></div>
                    </div>
                    <div class="row">
                        <div class="cell"><button id="clear-trans-selection">Clear Selection</button></div>
                    </div>
                </div>
            </div>

            <div id="trans_sidebar_inverse">

                <div class="row thead">
                    <div class="cell wid-10">Order ID</div>
                    <div class="cell wid-25">Date</div>
                    <div class="cell wid-25">Driver</div>
                    <div class="cell wid-20 cell-right">Breakdown</div>
                    <div class="cell wid-20 cell-right align-right">Amount</div>
                </div>
                @foreach ($payroll as $trans)
                    <div class="row payroll_row" data="$trans['delivery_fee']">
                        <div class="cell wid-10">{{$trans['order_id']}}</div>
                        <div class="cell wid-25">{{date('M d Y g:ia', strtotime($trans['time']))}}</div>
                        <div class="cell wid-25">{{$trans->driver->user['first_name']}} {{$trans->driver->user['last_name']}}</div>
                        <div class="cell cell-right wid-20">{{$trans['delivery_fee']}}  + {{$trans->order->cost['amount']}} + {{$trans['tip']}}</div>
                        <div class="cell cell-right align-right wid-20 payroll_amount strong" data="{{number_format(($trans['delivery_fee']  + $trans['amount'] + $trans['tip']), 2)}}">
                            {{number_format(($trans->order->cost['delivery_fee']  + $trans->order->cost['amount'] + $trans->order->cost['tip']), 2)}}
                        </div>
                    </div>
                @endforeach

            </div>

            <br class="clear"/>

        </div>
    </div>

@endsection
