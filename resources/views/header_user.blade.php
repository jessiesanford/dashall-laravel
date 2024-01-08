<div class="welcome resp_hide">
  <div class="username">{{ Auth::user()->first_name }}</div>
</div>

<div class="user-menu">
  <a class="resp_hide" href="./account"><span><fa class="fa fa-2x fa-cog"></i></span></a>
  @if (Auth::user()->user_group > 1)
    <a class="resp_hide" href="./driver"><fa class="fa fa-2x fa-taxi"></i></a>
    <a class="resp_hide" href="./schedule"><fa class="fa fa-2x fa-clock-o"></i></a>
  @endif
  @if (Auth::user()->user_group > 2)
  @endif
  <a class="toggle-main-sidebar" href="#"><i class="fa fa-2x fa-bars"></i></a>
</div>
