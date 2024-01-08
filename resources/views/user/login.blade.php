@extends('layouts.default')
@section('content')
  <div id="container">
    <div class="section wrap">
      <form method="post" id="login_form"  action="{{ url('/login') }}" class="sign_in align_center wid-50">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <label class="block push-bottom">
          <input type="email" class="textbox textbox_lrg wid-100" name="email" autocorrect="off" autocapitalize="off" placeholder="Email Address">
        </label>
        <label class="block push-bottom">
          <input type="password" class="textbox textbox_lrg wid-100" name="password" placeholder="Password">
        </label>
        <input type="submit" class="button button_lrg wid-100 push-top" value="Sign in">
        <hr />
        <p class="push-bottom"><a href="user?action=forgot_password"><i class="fa fa-key"></i>&nbsp; Forgot Password</a></p>
        <p>Don&rsquo;t have an account? <a href="user?action=register">Create One!</a></p>
      </form>
    </div>
  </div>
@stop
