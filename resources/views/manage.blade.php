@extends('layouts.default')
@section('content')
    <script type="text/javascript" src="./js/manage.js"></script>

    <div id="container">
        <div id="manage-section" class="section wrap">
            <h2 class="page-heading">Manage Orders</h2>
            {{--@forelse($filter as $status)--}}
                {{--{{$status}}--}}
                {{--@empty--}}
                    {{--Swag--}}
            {{--@endforelse--}}
            <div class="flex-row push-bottom-20">
                <div>{{ $orders->links() }}</div>
                <div class="cell-right"><button class="manage-filter-orders btn-alt"><i class="fa fa-filter push-right"></i> Filter Orders</button></div>
            </div>
            @foreach ($orders as $order)
                @include('manage.manage_order')
            @endforeach
        </div>
    </div>
@endsection
