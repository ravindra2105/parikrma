<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="navbar-header">
		<a class="navbar-brand" href="{{url('/')}}/home" style="margin-top:-12px">Parikrama</a>
	</div>

	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>

	<ul class="nav navbar-right navbar-top-links">
		
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="fa fa-user fa-fw"></i> {{ Auth::user()->name }} <b class="caret"></b>
			</a>
			<ul class="dropdown-menu dropdown-user">
				<li><a href="#"><i class="fa fa-user fa-fw"></i> change password</a>
				</li>				
				<li class="divider"></li>
				<li>
				<a class="dropdown-item" href="{{ route('logout') }}"
					onclick="event.preventDefault();
									document.getElementById('logout-form').submit();">
					{{ __('Logout') }}
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
					@csrf
				</form>				
				</li>
			</ul>
		</li>
	</ul>
	<!-- /.navbar-top-links -->

	<div class="navbar-default sidebar" role="navigation" style="overflow:auto">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav" id="side-menu">
				
				<li>
					<a href="{{ route('home') }}" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
				</li>

				@can('role-list')
				
				@endcan 

				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> User Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
							
						<li>
							<a href="{{ route('users.index') }}">Manage Users</a>
						</li>				
						@can('role-list')
						<li>
							<a href="{{ route('roles.index') }}"> Manage Role</a>
						</li>
						@endcan 
						
					</ul>
					<!-- /.nav-second-level -->
				</li>								
				
				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i>Registrations<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						
													
						<li>
							<a href="{{ route('registrations.index') }}">Registrations</a>
						</li>
						
					</ul>
					<!-- /.nav-second-level -->
				</li>

				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i>Subscription Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						
													
						<li>
							<a href="{{ route('subscriptions.index') }}">Subscription</a>
						</li>
						
					</ul>
					<!-- /.nav-second-level -->
				</li>
				
				
				

			</ul>
		</div>
	</div>
</nav>

<script>

</script>
