@extends('layouts.admin')
@section('content')
    @include('mustache.mus_transaction')
    <script type="text/javascript" src="./js/schedule.js"></script>

    <div id="container">
        <div class="section" id="settings_section">

            <div class="page-heading flex-row">
                <h2 class="page-title flex-cell">{{$title}}</h2>
                <div class="flex-cell cell-right">
                    <ul class="page-heading-menu">
                        <li><a href="./admin/schedule"><i class="fa fa-calendar push-right"></i> Back To Schedule</a></li>
                    </ul>
                </div>
            </div>

            @include('schedule.configuration')

        </div>
    </div>

@endsection
