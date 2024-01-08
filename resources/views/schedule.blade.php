@extends('layouts.default')
@section('content')
    <script type="text/javascript" src="./js/schedule.js"></script>
    <div id="container">
        <div class="section wrap">
            <div class="page-heading">
                <h1 class="page-title">Schedule</h1>
            </div>

            <form id="driver_settings" method="POST">

                <label class="label_boxed block push-bottom">
                    <input type="checkbox" class="checkbox" name="notify_orders" {{($driver['notify_orders'] == 1)?'checked':" "}} /> <strong>Notify me of any extra orders that need to be delivered.</strong>
                </label>
            </form>

            <p>
                To schedule yourself, simply click the box containing the shift you would like to be scheduled.
                A red box indicates that the shift is available for any driver to assign to themself.
            </p>

            @include('schedule.calendar')

            <br />
            <h3>Legend</h3>
            <div class="row no_border">
                <div>
                    <div class="calendar-shift calendar_legend inline_block">Shift Available</div>
                    <div class="calendar-shift calendar_legend shift_assigned inline_block">Shift Unavailable</div>
                    <div class="calendar-shift calendar_legend shift_self inline_block">Your Shift</div>
                </div>
            </div>

            <hr>
            <h3>Your Shifts</h3>
            <div id="shifts_table">
                <div class="row thead">
                    <div class="cell wid-25">Date</div>
                    <div class="cell wid-25">Start</div>
                    <div class="cell wid-25">End</div>
                </div>
            </div>

            @foreach ($driverShifts as $shift)
                <div class="row driver_shift {{($shift['req_unshift'] == 1) ? 'row_error' : ''}}" id="{{$shift['shift_id']}}">
                    <div class="cell wid-25">{{date('M d Y', strtotime($shift['start']))}}</div>
                    <div class="cell wid-25">{{date('g:ia', strtotime($shift['start']))}}</div>
                    <div class="cell wid-25">{{date('g:ia', strtotime($shift['end']))}}</div>
                    <div class="cell cell-right">
                        @if ($shift['req_unshift'] == 0)
                            <button class="confirm-action"
                                    data-action="request-unshift"
                                    data-desc="Please confirm your request to be unshifted."
                                    data-button="Confirm Unshift Request"
                                    data-value="{{$shift['shift_id']}}">
                                <i class="fa fa-times"></i>&nbsp; Unshift Me
                            </button>
                        @else
                            <i class="fa fa-clock-o"></i>&nbsp; Pending Unshift
                        @endif
                    </div>
                </div>
            @endforeach

        </div>

    </div>
@endsection
