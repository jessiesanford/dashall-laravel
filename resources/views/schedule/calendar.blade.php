<div id="calendar_weeks_view">
    <?php $index = 0; ?>


    @foreach ($calendar['weeks'] as $week)
        <div class="calendar_row thead resp_hide">
            @for ($i = 0; $i < 7; $i++)
                <div class="calendar_head_cell align_center  {{ ($week[$i]->format('D, M d') == $calendar['today']->format('D, M d') ? 'active_day' : '') }}">
                    {{$week[$i]->format('D, M d')}}
                </div>
            @endfor
        </div>
        <div class="calendar_row row-collapse" id="calendar_row">
            @foreach ($week as $day)
                <div class="calendar_day">
                    {{--{{dd($assignableShifts)}}--}}
                    @foreach ($assignableShifts[$index] as $shift)

                        @if ($day->format('D') == $shift['start']->format('D'))
                            {{--{{$shift['available']}}--}}
                        <div class="calendar-shift {{ ($shift['available'] == true ? 'confirm_shift' : 'shift_assigned') }}"
                             data-action="assign-shift"
                             data-desc=""
                             data-button="Shift Me"
                             data-start="{{$shift['start']}}"
                             data-end="{{$shift['end']}}">
                            {{$shift['start']->format('g:ia')}} - {{$shift['end']->format('g:ia')}}
                            <br />

                            @foreach ($shift['drivers'] as $driver)
                                {{$driver->driver->user['first_name']}}
                            @endforeach
                        </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
            <?php $index++; ?>

        </div>
    @endforeach
</div>