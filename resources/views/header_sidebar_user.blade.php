<div class="welcome push-bottom">
  <div class="greeting">Hello,</div><div class="username"><strong>{{ Auth::user()->first_name }}</strong></div>
</div>

<ul class="sidebar-menu">
  <li><a href="./account"><i class="fa fa-user"></i>&nbsp; Account</a></li>
  <li><a href="./restaurants"><i class="fa fa-cutlery"></i>&nbsp; Restaurants</a></li>
  <li><a href="./driver"><i class="fa fa-shopping-cart"></i>&nbsp; Orders</a></li>
  <li><a href="./schedule"><i class="fa fa-clock-o"></i>&nbsp; Schedule</a></li>
  <li><a href="./manage"><i class="fa fa-flag"></i>&nbsp; Manage</a></li>
  <li><a href="./admin"><i class="fa fa-cogs"></i>&nbsp; Admin</a></li>
  <li><a class="item" href="#" id="user_logout" data-token="{{ csrf_token() }}"><i class="fa fa-sign-out"></i>&nbsp; Sign Out</a></li>
</ul>

