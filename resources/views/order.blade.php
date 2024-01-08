@extends('layouts.default')
@section('content')
    {{--<script type="text/javascript" src="./js/order.js"></script>--}}
    <div id="showcase_backdrop" class="resp_hide">
        <div class="overlay"></div>
    </div>

    <div id="order-flow" class="push-bottom-40">
        @if ( empty($order) )
            @include('order.order_init')
        @else
            @include('order.order_flow')
        @endif
    </div>
@endsection
