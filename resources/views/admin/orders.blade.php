@extends('layouts.admin')
@section('content')
    @include('mustache.mus_order')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="./js/admin_orders.js"></script>

    <div id="container">
        <div class="section" id="settings_section">

            <div class="page-heading">
                <h1 class="page-title">{{$title}}</h1>
            </div>

            <form id="admin-create-order">
                <button type="submit">Create Test Order</button>
            </form>

            <br/>

            <div class="flex-row thead">
                <div class="cell">Operations</div>
            </div>

            <div class="flex-row">
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

            <div id="order_stats" class="flex-row row_baseline">
                <div class="flex-cell wid-30">
                    <div>
                        Total Orders
                        <div class="stat_big">
                            <span id="total_orders"></span> Orders
                        </div>
                    </div>
                    <div class="push-top-20">
                        Total Complete Orders
                        <div class="stat_big">
                            <span id="total_complete_orders"></span> Orders
                        </div>
                    </div>
                </div>
                <div class="flex-cell wid-30">
                    <div>
                        Average Order Time
                        <div class="stat_big">
                            <span id="avg_order_time"></span> Minutes
                        </div>
                    </div>
                </div>
                <div class="flex-cell wid-30">
                    <div class="push-top-20">
                        Repeat Customer Orders
                        <div class="stat_big">
                            <span id="repeat_customer_orders"></span> Repeat Orders
                        </div>
                    </div>
                    <div class="push-top-20">
                        Repeat Customer Count
                        <div class="stat_big">
                            <span id="repeat_customer_count"></span> Repeat Customers
                        </div>
                    </div>
                </div>
            </div>

            <div id="chart_div"></div>


            <div class="flex-row">
                <div class="cell cell-right align-right">
                    <button id="reset_orders">Reset</button>
                </div>
            </div>

            <br />

            <div class="flex-row thead">
                <div class="flex-cell wid-10">Order ID</div>
                <div class="flex-cell wid-10">Status</div>
                <div class="flex-cell wid-10">Location</div>
                <div class="flex-cell wid-20">Time</div>
                <div class="flex-cell wid-20">User</div>
                <div class="flex-cell wid-20">Address</div>
            </div>

            <div id="orders_view">
            </div>

        </div>
    </div>

@endsection
