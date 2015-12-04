<?php
	require_once(__DIR__ . "/../../../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\ReportController($pdo);

	if (\LIS\Utility::requestHasPost())
		$checkouts = $controller->generateRentalItemReport($_POST["rentalItemID"]);

	$page_title = "Rentel Item Report";
?>
<!doctype html>
<html lang="en">
<head>
	<?php require_once(__DIR__ . "/../../../../includes/html_templates/head.php"); ?>
</head>
<body>
<header class="navbar navbar-fixed-top navbar-inverse">
	<?php require_once(__DIR__ . "/../../../../includes/html_templates/header.php"); ?>
</header>
<div class="container">
	<div class="container-fluid">
		<?php if ($controller->getSessionUser()->isEmployee() || $controller->getSessionUser()->isAdmin()) { ?>
		<div class="row">
			<?php require_once(__DIR__ . "/../../../../includes/html_templates/control_panel_nav.php"); ?>
			<div class="center col-lg-8 col-md-8 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-1">
				<h1 class="page-header">Enter Rental Item ID</h1>
				<form action method="post">

					<?php if ($controller->hasError()) { ?>
						<p class="alert bg-danger">
							<?= $controller->getErrorMessage(); ?>
						</p>
					<?php } ?>
					<div class="form-group">
						<label for="title">Rental Item ID</label>
						<input type="text" class="form-control" id="title" name="rentalItemID"
						       placeholder="1234" value="<?= $_POST["rentalItemID"] ?>">
					</div>
					<button type="submit" class="btn btn-default">Create</button>
				</form>
				<h2 class="sub-header">Rental Item Report</h2>
				<div class="table-responsive">
					<table class="table table-striped" id="view_table">
						<thead>
							<tr>
								<th>User</th>
								<th>Checkout Date</th>
								<th>Due Date</th>
								<th>Checkin Date</th>
							</tr>
						</thead>
						<tbody id="available">
							<?php if (isset($checkouts)) { ?>
								<?php foreach ($checkouts as $checkout) { ?>
									<tr>
										<td>
											<a href="/controlpanel/users/<?= $checkout->getUserID() ?>/">
												<?= $checkout->getUser()->getNameFull() ?>
											</a>
										</td>
										<td><?= $checkout->getDateCheckedOut()->format("m-d-Y H:i:s") ?></td>
										<td><?= $checkout->getDateDue()->format("m-d-Y") ?></td>
										<td>
											<?php if ($checkout->getDateReturned()) { ?>
												<?= $checkout->getDateReturned()->format("m-d-Y H:i:s") ?>
											<?php } ?>
										</td>
									</tr>
								<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php }
		else { ?>
			<h4 class="alert bg-warning">
				You do not have permission to view this page.
			</h4>
		<?php } ?>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../../../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>
