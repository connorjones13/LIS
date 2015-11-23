<!-- TEMPLATE -->
<?php
require_once(__DIR__ . "/../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\BaseController($pdo);

$page_title = "Control Panel";
?>
<!doctype html>
<html lang="en">
<head>
	<?php require_once(__DIR__ . "/../../includes/html_templates/head.php"); ?>
</head>
<body>
<header class="navbar navbar-fixed-top navbar-inverse">
	<?php require_once(__DIR__ . "/../../includes/html_templates/header.php"); ?>
</header>
<div class="content">
	<div class="container-fluid">
		<?php if ($controller->getSessionUser()->isEmployee() || $controller->getSessionUser()->isAdmin()) { ?>
		<div class="row">
			<?php require_once(__DIR__ . "/../../includes/html_templates/control_panel_nav.php"); ?>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

				<h1 class="page-header">Control Panel</h1>

				<!-- todo: replace static number placeholders with database queries -->
				<div class="row placeholders">
					<div class="col-xs-6 col-sm-3 placeholder">
						<h2>19,431</h2>
						<h4>Available Items</h4>
<!--						<span class="text-muted">Something else</span>-->
					</div>
					<div class="col-xs-6 col-sm-3 placeholder">
						<h2>357</h2>
						<h4>Checked Out Items</h4>
<!--						<span class="text-muted">Something else</span>-->
					</div>
					<div class="col-xs-6 col-sm-3 placeholder">
						<h2>10</h2>
						<h4>Damaged Items</h4>
<!--						<span class="text-muted">Something else</span>-->
					</div>
					<div class="col-xs-6 col-sm-3 placeholder">
						<h2>184</h2>
						<h4>Lost Items</h4>
<!--						<span class="text-muted">Something else</span>-->
					</div>
				</div>

				<h2 class="sub-header">Active Reservations</h2>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
						<tr>
							<th>#</th>
							<th>Header</th>
							<th>Header</th>
							<th>Header</th>
							<th>Header</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>1,001</td>
							<td>Lorem</td>
							<td>ipsum</td>
							<td>dolor</td>
							<td>sit</td>
						</tr>
						<tr>
							<td>1,002</td>
							<td>amet</td>
							<td>consectetur</td>
							<td>adipiscing</td>
							<td>elit</td>
						</tr>
						<tr>
							<td>1,003</td>
							<td>Integer</td>
							<td>nec</td>
							<td>odio</td>
							<td>Praesent</td>
						</tr>
						<tr>
							<td>1,003</td>
							<td>libero</td>
							<td>Sed</td>
							<td>cursus</td>
							<td>ante</td>
						</tr>
						<tr>
							<td>1,004</td>
							<td>dapibus</td>
							<td>diam</td>
							<td>Sed</td>
							<td>nisi</td>
						</tr>
						<tr>
							<td>1,005</td>
							<td>Nulla</td>
							<td>quis</td>
							<td>sem</td>
							<td>at</td>
						</tr>
						<tr>
							<td>1,006</td>
							<td>nibh</td>
							<td>elementum</td>
							<td>imperdiet</td>
							<td>Duis</td>
						</tr>
						<tr>
							<td>1,007</td>
							<td>sagittis</td>
							<td>ipsum</td>
							<td>Praesent</td>
							<td>mauris</td>
						</tr>
						<tr>
							<td>1,008</td>
							<td>Fusce</td>
							<td>nec</td>
							<td>tellus</td>
							<td>sed</td>
						</tr>
						<tr>
							<td>1,009</td>
							<td>augue</td>
							<td>semper</td>
							<td>porta</td>
							<td>Mauris</td>
						</tr>
						<tr>
							<td>1,010</td>
							<td>massa</td>
							<td>Vestibulum</td>
							<td>lacinia</td>
							<td>arcu</td>
						</tr>
						<tr>
							<td>1,011</td>
							<td>eget</td>
							<td>nulla</td>
							<td>Class</td>
							<td>aptent</td>
						</tr>
						<tr>
							<td>1,012</td>
							<td>taciti</td>
							<td>sociosqu</td>
							<td>ad</td>
							<td>litora</td>
						</tr>
						<tr>
							<td>1,013</td>
							<td>torquent</td>
							<td>per</td>
							<td>conubia</td>
							<td>nostra</td>
						</tr>
						<tr>
							<td>1,014</td>
							<td>per</td>
							<td>inceptos</td>
							<td>himenaeos</td>
							<td>Curabitur</td>
						</tr>
						<tr>
							<td>1,015</td>
							<td>sodales</td>
							<td>ligula</td>
							<td>in</td>
							<td>libero</td>
						</tr>
						</tbody>
					</table>
				</div>

			</div>
		</div>
		<?php } else { ?>
			<h4 class="alert bg-warning">
				You do not have permission to view this page.
			</h4>
		<?php } ?>
	</div>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>