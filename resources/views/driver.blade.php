@extends('layouts.default')
@section('content')

    <div id="container">
        <div id="driver-section" class="section wrap">
            <h2 class="page-heading">Ongoing Orders</h2>

            <ul class="tab-menu tab-menu-across push-bottom-40">
                <li class="selected" data="tab-1">Your Orders</li>
                <li data="tab-2">Unassigned ({{$count_unassigned_orders}})</li>
            </ul>

            <div class="tab-panel tab-current" id="tab-1">
                @if (count($assigned_orders) > 0)
                    @foreach($assigned_orders as $order)
                        @include('driver.driver_order')
                    @endforeach
                @else
                    You do not have any assigned orders.
                @endif
            </div>

            <div class="tab-panel" id="tab-2">
                @if (count($unassigned_orders) > 0)
                @foreach($unassigned_orders as $order)
                    @include('driver.driver_order_unassigned')
                @endforeach
                @else
                    <div class="align-center">
                        There are currently no outstanding orders to be assigned.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
