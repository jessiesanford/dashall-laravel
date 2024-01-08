@extends('layouts.admin')
@section('content')
    <script type="text/javascript" src="./js/schedule.js"></script>

    <div id="container">
        <div class="section" id="settings_section">

            <div class="page-heading flex-row">
                <h2 class="page-title flex-cell ">{{$title}}</h2>
                <div class="flex-cell cell-right">
                    <ul class="page-heading-menu">
                        <li><a href="./admin/schedule/configure"><i class="fa fa-cog push-right"></i> Configure Schedule</a></li>
                    </ul>
                </div>
            </div>

            @include('schedule.calendar')

            <hr>
            <h3>Manage Shifts</h3>
            <div id="shifts_table">
                <div class="row thead">
                    <div class="cell wid-25">Date</div>
                    <div class="cell wid-25">Start</div>
                    <div class="cell wid-25">End</div>
                </div>
                @foreach ($driverShifts as $shift)
                    <div class="row driver_shift {{($shift['req_unshift'] == 1) ? 'row_error' : ''}}" id="{{$shift['shift_id']}}">
                        <div class="cell wid-25">{{date('M d Y', strtotime($shift['start']))}}</div>
                        <div class="cell wid-25">{{date('g:ia', strtotime($shift['start']))}}</div>
                        <div class="cell wid-25">{{date('g:ia', strtotime($shift['end']))}}</div>
                        <div class="cell cell-right">
                            @if($shift['req_unshift'] == 1)
                                <button class="remove-shift"><i class="fa fa-times push-right"></i> Approve Removal</button>
                            @else
                                <button class="remove-shift btn-small"><i class="fa fa-times"></i></button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

@endsection
