@extends('layouts.admin')
@section('content')
    <div id="container">
        <div class="section" id="settings_section">

            <div class="page-heading">
                <h1 class="page-title">{{$title}}</h1>
            </div>

            <form id="mass_text_drivers">

                <div class="row" style="align-items: flex-start">
                    <div class="cell">
                        <textarea name="message" class="block" style="min-width: 400px; min-height: 200px;"></textarea>
                        <button type="submit" class="push-top-20">Send Message</button>
                    </div>
                    <div class="cell">
                        <select name="driver[]" style="min-height: 200px;" multiple>
                            @foreach ($drivers as $driver)
                                <option value="{{$driver['user_id']}}" >{{$driver->user['first_name']}} {{$driver->user['last_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection
