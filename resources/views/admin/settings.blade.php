@extends('layouts.admin')
@section('content')
    <script type="text/javascript" src="./js/manage.js"></script>

    <script type="text/javascript" src="./js/admin_settings.js"></script>

    <div id="container">
        <div class="section" id="settings_section">

            <div class="page-heading">
                <h1 class="page-title">Global Settings</h1>
            </div>

            <div class="flex-row row-collapse thead {{ $store_hours->is_open() || $settings['force_operation'] ? 'settings_heading_open' : '' }}  ">
                <div class="cell">
                    @if ($settings['open_notice'] == 1)
                        {{$settings['open_notice']}}
                    @elseif ($settings['open_notice'] == 0 && $settings['force_operation'] == 1)
                        {{$settings['open_notice']}}
                    @else
                        {{$settings['closed_notice']}}
                    @endif
                </div>
                <div class="cell cell-right {{ $settings['force_operation'] ? 'settings_heading_forced' : '' }}">
                    @if ($settings['open_notice'] == 0 && $settings['force_operation'] == 1)
                        <i class="fa fa-exclamation-circle push-right"></i> <strong>FORCED OPEN STATUS ENABLED</strong>
                    @endif
                </div>
            </div>

            <form id="update_settings">

                <div class="thead cell"><strong>Operation Settings</strong></div>

                <div class="row row-collapse">
                    <div class="cell wid-50">
                        <div>Enable Timed Opening/Closing (Set to "No" to close DashAll)</div>
                    </div>
                    <div class="cell wid-50">
                        <select id="taking_orders" name="taking_orders">
                            @if ($settings['taking_orders'] == 1)
                                <option value="1" selected>Yes</option>
                                <option value="0">No</option>
                            @else
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="row row-collapse">
                    <div class="cell wid-50">
                        <div>Force Open Status (Set to "Yes" to force open DashAll)</div>
                    </div>
                    <div class="cell wid-50">
                        <select id="force_operation" name="force_operation">
                            @if ($settings['force_operation'] == 1)
                                <option value="1" selected>Yes</option>
                                <option value="0">No</option>
                            @else
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="row row-collapse">
                    <div class="cell wid-50">
                        <div>Management Mode</div>
                    </div>
                    <div class="cell wid-50">
                        <select id="management_mode" name="management_mode">
                            @if ($settings['management_mode'] == 0)
                                <option value="0" selected>Manual (does not automatically delegate orders)</option>
                                <option value="1">Active (delegates orders to specific driver(s))</option>
                                <option value="2">Passive (delegates orders to any drivers that are active)</option>
                                <option value="3">Scheduled (delegate orders to scheduled driver)</option>
                            @elseif ($settings['management_mode'] == 1)
                                <option value="0">Manual (does not automatically delegate orders)</option>
                                <option value="1" selected>Active (delegates orders to specific driver(s))</option>
                                <option value="2">Passive (delegates orders to any drivers that are active)</option>
                                <option value="3">Scheduled (delegate orders to scheduled driver)</option>

                            @elseif ($settings['management_mode'] == 2)
                                <option value="0">Manual (does not automatically delegate orders)</option>
                                <option value="1">Active (delegates orders to specific driver(s))</option>
                                <option value="2" selected>Passive (delegates orders to any drivers that are active)</option>
                                <option value="3">Scheduled (delegate orders to scheduled driver)</option>

                            @else
                                <option value="0">Manual (does not automatically delegate orders)</option>
                                <option value="1">Active (delegates orders to specific driver(s))</option>
                                <option value="2">Passive (delegates orders to any drivers that are active)</option>
                                <option value="3" selected>Scheduled (delegate orders to scheduled driver)</option>
                            @endif
                        </select>
                    </div>
                </div>

                @if ($settings['management_mode'] == 1)
                    <div class="row row-collapse">
                        <div class="cell wid-50">
                            Active Driver Selection
                        </div>
                        <div class="cell wid-50">
                            <select id="active_driver" name="active_driver">
                            @foreach ($drivers as $driver)
                                <option value="{{$driver['driver_id']}}" {{ $activeDriver->driver_id == $driver['driver_id'] ? 'selected' : '' }}>{{$driver->user['first_name']}} {{$driver->user['last_name']}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row row-collapse">
                        <div class="cell wid-50">
                            Current Active Driver
                        </div>
                        <div class="cell wid-50">
                        <strong>{{$activeDriver->user['first_name']}} {{$activeDriver->user['last_name']}}</strong>
                        </div>
                    </div>
                @elseif ($settings['management_mode'] == 3)
                    <div class="row row-collapse">
                        <div class="cell wid-50">Scheduled Drivers</div>
                        <div class="cell wid-50">
                            @if ($todaysShifts->isEmpty())
                                No Shifts Scheduled
                            @endif
                            @foreach ($todaysShifts as $shift)
                                <div class="padd_5">
                                    <span class="scheduled_time {{date('h:i', strtotime($shift['start'])) < date('h:i', strtotime($time)) &&  date('h:i', strtotime($shift['end'])) > date('h:i', strtotime($time)) ? 'scheduled_time_active' : ''}}">{{date('h:i', strtotime($shift['start']))}} - {{date('h:i', strtotime($shift['end']))}}</span>
                                    <strong>{{$shift->driver->user['first_name']}} {{$shift->driver->user['last_name']}}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif


                <div class="row row-collapse">
                    <div class="cell wid-50">
                        Open Notice
                    </div>
                    <div class="cell wid-50">
                        <textarea class="textarea" id="open_notice" name="open_notice" placeholder="Notice goes here...">{{$settings['open_notice']}} </textarea>
                    </div>
                </div>
                <div class="row row-collapse">
                    <div class="cell wid-50">
                        Closed Notice
                    </div>
                    <div class="cell wid-50">
                        <textarea class="textarea" id="closed_notice" name="closed_notice" placeholder="Notice goes here...">{{$settings['closed_notice']}}</textarea>
                    </div>
                </div>

                <div class="thead cell"><strong>Variable Settings</strong></div>

                <div class="row row-collapse">
                    <div class="cell wid-50">
                        Order Margin
                    </div>
                    <div class="cell wid-50">
                        <div class="textbox-container">% <input class="textbox" name="stripe_margin" value="{{$settings['order_margin']}}" size="2"/></div>
                    </div>
                </div>

                <div class="row row-collapse">
                    <div class="cell wid-50">
                        Stripe Margin
                    </div>
                    <div class="cell wid-50">
                        <div class="textbox-container">% <input class="textbox" name="stripe_margin" value="{{$settings['stripe_margin']}}" size="2"/></div>
                    </div>
                </div>

                <div class="row row-collapse">
                    <div class="cell wid-50">
                        Base Delivery Fee
                    </div>
                    <div class="cell wid-50">
                        <div class="textbox-container">$ <input class="textbox" name="base_delivery_fee" value="{{$settings['base_delivery_fee']}}" size="10"/></div>
                    </div>
                </div>
                <hr />
                <div class="align-center">
                    <button class="button">Update Settings</button>
                    <button class="button" type="reset">Cancel</button>
                </div>
            </form>

        </div>
    </div>

@endsection
