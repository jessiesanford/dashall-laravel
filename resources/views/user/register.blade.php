@extends('layouts.default')
@section('content')
    <div id="container">
        <div class="section wrap">
            <div class="section">
                <h2 class="page-heading">Create Account</h2>
                <form method="post" id="register_form" class="register">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row no_border">
                        <label class="cell wid-100 block">
                            <div>Email Address</div>
                            <input type="email" class="textbox textbox_block" name="email" id="email" autocorrect="off" autocapitalize="off" placeholder="name@website.com">
                        </label>
                    </div>
                    <div class="row row-collapse no_border">
                        <label class="cell wid-100 block">
                            <div>Phone Number</div>
                            <input type="tel" class="textbox textbox_block" name="phone" id="phone" maxlength="12" placeholder="(709) 111-2222">
                        </label>
                    </div>
                    <div class="row row-collapse no_border">
                        <label class="cell wid-50 block">
                            <div>Password</div>
                            <input type="password" class="textbox textbox_block" name="password" id="password" placeholder="Minimum of 5 characters">
                        </label>
                        <label class="cell wid-50 block">
                            <div>Password Again</div>
                            <input type="password" class="textbox textbox_block" name="password_confirmation" id="password_confirmation" placeholder="Minimum of 5 characters">
                        </label>
                    </div>
                    <div class="row row-collapse no_border">
                        <label class="cell wid-50 block">
                            <div>First Name</div>
                            <input type="text" class="textbox textbox_block" name="first_name" id="first_name" autocapitalize="words" placeholder="First" />
                        </label>
                        <label class="cell wid-50 block">
                            <div>Last Name</div>
                            <input type="text" class="textbox textbox_block" name="last_name" id="last_name" autocapitalize="words" placeholder="Last" />
                        </label>
                    </div>
                    <label class="row no_border">
                        <div class="cell wid-10">
                            <input type="checkbox" name="survey" checked />
                        </div>
                        <div class="cell cell-grow">
                            Would you like to be surveyed? (We will give you coupons for free delivery and bonus promotions!)
                        </div>
                    </label>
                    <div class="row row-collapse no_border">
                        <label class="cell block">
                            <div>How did you hear about us?</div>
                            <select name="refer_source" class="block">
                                <option selected disabled>Please choose one...</option>
                                <option>Friends</option>
                                <option>Posters</option>
                                <option>Google</option>
                                <option>Facebook (DashAll)</option>
                                <option>Facebook (Other)</option>
                            </select>
                        </label>
                    </div>

                    <input type="hidden" name="referral" id="referral" placeholder="Referral ID" value="refer code here" />
                    <div class="row row-collapse no_border">
                        <label class="cell wid-100 block">
                            <div>Referral</div>
                            <input type="textbox" class="textbox textbox_block" name="referral" id="referral" placeholder="Referral ID" value="" />
                        </label>
                    </div>
                    <hr />
                    <input type="submit" class="button button_lrg wid-100" value="Create Account" />
                </form>
            </div>
        </div>
    </div>
@stop