<div class="container">
	<div class="container-fluid">
		<!-- logo and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
			        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a id="logo" href="/">LIS</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="/browse/">Browse</a></li>
				<?php if ($controller->isLoggedIn() && $controller->getSessionUser()->getPrivilegeLevel() > 0) { ?>
					<li><a href="/controlpanel">Control Panel</a></li>
				<?php } ?>
				<?php if ($controller->getSessionUser()) { ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							Account <b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a href="/controlpanel/users/<?= $controller->getSessionUser()->getId() ?>/">Settings</a></li>
							<li><a href="/controlpanel/checkout_checkin_status/">Status Report</a></li>
							<li class="divider"></li>
							<li>
								<a href="/logout">Log out</a>
							</li>
						</ul>
					</li>
				<?php } else { ?>
					<li><a href="/login">Log in</a></li>
					<li><a href="/account/create">Sign Up</a></li>
				<?php } ?>
			</ul>
			<form action="/search" method="get" class="navbar-form navbar-right" role="search">
				<div class="form-group">
					<input type="text" class="form-control" name="s" placeholder="Search" value="<?= $_GET["s"] ?>">
				</div>
				<button type="submit" class="btn btn-default">Search</button>
			</form>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</div>