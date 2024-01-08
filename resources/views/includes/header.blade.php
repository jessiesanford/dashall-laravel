<div id="main-sidebar">
	<div class="sidebar-content">
		<div class="sidebar-heading">
			<a href="#" class="toggle-main-sidebar push-right"><i class="fa fa-times"></i></a> Navigation
		</div>
		@if (Auth::guest())
			@include('./header_sidebar_guest')
		@else
			@include('./header_sidebar_user')
		@endif
	</div>
</div>

<div class="popup">
	<span></span>
</div>

<div id="mask"></div>
<div id="model"></div>

<div id="loading">
	<img src="./img/loading.gif" alt="Loading..." />
</div>

<div id="header">
	<div class="wrap">
		<div class="header-top flex-row">
			<div class="cell">
				<a href="./" id="bridge_logo"><img src="./img/logo-alt.png" alt="DashAll" /></a>
			</div>
			<div class="cell cell-grow align-right">
				<div id="user-panel">
					@if (Auth::guest())
						@include('header_guest')
					@else
						@include('header_user')
					@endif
				</div>
			</div>
		</div>
		<div class="header-bottom flex-row">
			<ul class="header-menu">
				<li><a href="./order"><i class="fa fa-shopping-cart push_right"></i> Order</a></li>
				<li><a href="./restaurants"><i class="fa fa-cutlery push_right"></i> Restaurants</a></li>
			</ul>
		</div>
	</div>
</div>

<div class="notice {{ $settings['taking_orders'] ? 'online' : '' }} ">
	@if ($online)
		{{$settings['open_notice']}}
	@else
		{{$settings['closed_notice']}}
	@endif
</div>
