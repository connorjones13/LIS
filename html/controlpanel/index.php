<!-- TEMPLATE -->
<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\BaseController($pdo);

	if (is_null($controller->getSessionUser()) || $controller->getSessionUser()->getPrivilegeLevel() < \LIS\User\User::PRIVILEGE_EMPLOYEE) {
		header("Location: /");
		exit();
	}

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
	<script type="text/javascript">
		$(".show_data").click(function() {

		});
	</script>
</header>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<?php require_once(__DIR__ . "/../../includes/html_templates/control_panel_nav.php"); ?>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

				<h1 class="page-header">Control Panel</h1>

				<div class="row placeholders">
					<div class="col-xs-6 col-sm-3 placeholder show_data" data-view="available">
						<h2><?= \LIS\Utility::formatNumber(\LIS\RentalItem\RentalItem::getAvailableItemCount($pdo)) ?></h2>
						<h4>Available Items</h4>
						<span class="text-muted">Click to View</span>
					</div>
					<div class="col-xs-6 col-sm-3 placeholder show_data" data-view="checked_out">
						<h2><?= \LIS\Utility::formatNumber(\LIS\Checkout::getItemCheckedOutCount($pdo)) ?></h2>
						<h4>Checked Out Items</h4>
						<span class="text-muted">Click to View</span>
					</div>
					<div class="col-xs-6 col-sm-3 placeholder show_data" data-view="damaged">
						<h2><?= \LIS\Utility::formatNumber(\LIS\RentalItem\RentalItem::getDamagedItemCount($pdo)) ?></h2>
						<h4>Damaged Items</h4>
						<span class="text-muted">Click to View</span>
					</div>
					<div class="col-xs-6 col-sm-3 placeholder show_data" data-view="lost">
						<h2><?= \LIS\Utility::formatNumber(\LIS\RentalItem\RentalItem::getLostItemCount($pdo)) ?></h2>
						<h4>Lost Items</h4>
						<span class="text-muted">Click to View</span>
					</div>
				</div>

				<h2 class="sub-header">Available Items</h2>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>ID#</th>
								<th>Title</th>
								<th>Category</th>
								<th>Creators</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach (\LIS\RentalItem\RentalItem::getAllAvailable($pdo) as $item) { ?>
								<tr>
									<td><?= \LIS\Utility::formatNumber($item->getId()) ?></td>
									<td><?= $item->getTitle() ?></td>
									<td><?= $item->getCategory() ?></td>
									<?php if ($item->isBook()) { ?>
										<td><?= implode(", ", \LIS\RentalItem\Author::findAllForBook($pdo, $item)) ?></td>
									<?php } else if ($item->isDVD()) { ?>
										<td><?= $item->getDirector() ?></td>
									<?php } else if ($item->isMagazine()) { ?>
										<td><?= $item->getPublication() ?> (#<?= $item->getIssueNumber() ?>)</td>
									<?php } ?>
									<td></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>