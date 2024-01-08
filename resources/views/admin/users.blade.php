@extends('layouts.admin')
@section('content')
    @include('mustache.mus_transaction')
    <script type="text/javascript" src="./js/stats.js"></script>

    <div id="container">
        <div class="section orders_section">
            <div class="flex-row push-bottom-20">
                <div class="count_block">
                    Registered Users
                    <div class="counter_count">{{$userCount}}</div>
                </div>

                <form id="user_search" class="cell-right">
                    <input type="textbox" class="textbox" placeholder="Search..." name="search_query" />
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <div id="user_table" class="table">
                <div class="row thead">
                    <div class="cell wid-25">Email</div>
                    <div class="cell wid-25">Name</div>
                    <div class="cell wid-25">Phone</div>
                    <div class="cell wid-25 resp_hide">Date</div>
                </div>
                <div class="rows">
                    @foreach ($users as $user)
                        <div class="row row-collapse">
                            <div class="cell wid-25">{{$user['email']}}</div>
                            <div class="cell wid-25"><a href="admin/users/{{$user['user_id']}}">{{$user['first_name']}} {{$user['last_name']}}</a></div>
                            <div class="cell wid-25">{{$user['phone']}}</div>
                            <div class="cell resp_hide wid-25">{{date('M d Y - g:ia', strtotime($user['reg_date']))}}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="push-top-20 align-center">
                {{$users->links()}}
            </div>

            </div>
        </div>
    </div>

@endsection
