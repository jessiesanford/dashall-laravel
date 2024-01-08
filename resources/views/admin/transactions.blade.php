@extends('layouts.admin')
@section('content')
    @include('mustache.mus_transaction')
    @include('mustache.mus_order')
    {{--<script type="text/javascript" src="./js/transactions.js"></script>--}}
    {{--<link rel="stylesheet" href="./css/admin_transactions.css" />--}}

    <div id="container">
        <div class="section" id="settings_section">

            <div class="page-heading">
                <h1 class="page-title">{{$title}}</h1>
            </div>

            <div class="row thead">
                <div class="cell">Operations</div>
            </div>
            <div class="row">
                <div class="cell">
                    <label class="block">
                        <div>Start Date</div>
                        <input type="text" class="textbox" id="start_date" placeholder="Beginning of time" readonly>
                    </label>
                </div>
                <div class="cell">
                    <label class="block">
                        <div>End Date</div>
                        <input type="text" class="textbox" id="end_date" placeholder="End of time" readonly>
                    </label>
                </div>
            </div>
            <div id="trans_stats" class="row row_baseline">

                <div class="cell wid-30">
                    <div>
                        Revenue
                        <div class="stat_big"><span id="rev_amount">$</span></div>
                    </div>
                    <div class="push-top-20">
                        Profit
                        <div class="stat_big"><span id="profit_amount">$</span></div>
                    </div>
                </div>
                <div class="cell wid-30">
                    <div >
                        FIRSTDASH
                        <div class="stat_big"><span id="firstdash_count"></span> Times</div>
                    </div>
                    <div class="push-top-20">
                        DASHCASH
                        <div class="stat_big"><span id="dashcash_count"></span> Times</div>
                    </div>
                </div>
                <div class="cell wid-30">
                    <div>
                        Total Orders
                        <div class="stat_big"><span id="order_count"></span> Orders</div>
                    </div>
                    <div class="push-top-20">
                        Repeat Customer Orders
                        <div class="stat_big"><span id="repeat_customer_orders"></span> Orders</div>
                    </div>
                </div>
                <div class="cell wid-30">
                    <div>
                        Average Order Cost
                        <div class="stat_big"><span id="avg_order_cost">$</span></div>
                    </div>
                    <div class="push-top-20">
                        Average Profit
                        <div class="stat_big"><span id="avg_profit">$</span></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="cell cell-right align-right">
                    <button id="reset_transactions">Reset</button>
                </div>
            </div>

            <br>

            <div id="transactions_wrapper">

                <div class="row thead">
                    <div class="cell wid-10">Order ID</div>
                    <div class="cell wid-25">Date</div>
                    <div class="cell wid-25">Customer</div>
                    <div class="cell wid-15">Promo</div>
                    <div class="cell wid-20 cell-right align-right">Profit</div>
                </div>

                <div id="transactions_view">

                </div>
            </div>

        </div>

        </div>
    </div>

@endsection
