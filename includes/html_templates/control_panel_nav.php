<div class="col-sm-3 col-md-2 sidebar">
	<ul class="nav nav-sidebar">
		<li <?php if($page_title == 'Control Panel') echo 'class="active"'?>>
			<a href="/controlpanel/">Overview</a></li>
		<li <?php if($page_title == 'Reports') echo 'class="active"'?>>
			<a href="#">Reports</a></li>
		<li <?php if($page_title == 'Add Rental Item') echo 'class="active"'?>>
			<a href="/controlpanel/add/">Add Rental Item</a></li>
			<!--<ul class="nav nav-sidebar">
				<li><a href="/controlpanel/add/index.php">Add Magazine</a></li>
				<li><a href="/controlpanel/add/index.php">Add Book</a></li>
				<li><a href="/controlpanel/add/index.php">Add DVD</a></li>
			</ul> -->
		<li <?php if($page_title == 'Manage Users') echo 'class="active"'?>>
			<a href="">Manage Users</a></li>
		<li <?php if($page_title == 'Checkout') echo 'class="active"' ?>>
			<a href="/controlpanel/checkout">Checkout</a></li>
		<li <?php if($page_title == 'Check In') echo 'class="active"' ?>>
			<a href="/controlpanel/checkin">Check In</a></li>
	</ul>
</div>