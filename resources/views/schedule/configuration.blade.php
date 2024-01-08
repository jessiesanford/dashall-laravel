<div class="week-view">

    <div class="week-row resp-hide">
        @foreach ($daysOfWeek as $day)
            <div class="week-cell align_center ">
                <div class="week-cell-heading">{{$day}}</div>
                @foreach ($shifts as $shift)
                    @if ($shift->day == $day)
                        <div class="week-shift" data-day="{{$day}}" data-start="{{$shift->start}}" data-end="{{$shift->end}}">
                            {{date('g:ia', strtotime($shift->start))}} - {{date('g:ia', strtotime($shift->end))}}
                        </div>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>

    <div id="delete-shift" class="push-top-10">
        <i class="fa fa-lg fa-trash"></i>
    </div>

</div>

<hr>

<h3>Create Shift</h3>
<form action="POST" id="create-shift">
    <label class="inline-block">
        <div>Day</div>
        <select name="shift_day">
            @foreach ($daysOfWeek as $day)
                <option>
                    {{$day}}
                </option>
            @endforeach
        </select>
    </label>


    <label class="inline-block">
        <div>Start Time</div>
        <select name="shift_start">
            @foreach ($hours as $hour)
                <option value="{{date('H:i:s', strtotime($hour))}}">
                    {{$hour}}
                </option>
            @endforeach
        </select>
    </label>

    <label class="inline-block">
        <div>End Time</div>
        <select  name="shift_end">
            @foreach ($hours as $hour)
                <option value="{{date('H:i:s', strtotime($hour))}}">
                    {{$hour}}
                </option>
            @endforeach
        </select>
    </label>
    <button type="submit">Create Shift</button>
</form>