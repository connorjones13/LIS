<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\BaseController($pdo);

	if (is_null($controller->getSessionUser())
		|| $controller->getSessionUser()->getPrivilegeLevel() < \LIS\User\User::PRIVILEGE_EMPLOYEE) {
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
</header>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			<?php require_once(__DIR__ . "/../../includes/html_templates/control_panel_nav.php"); ?>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

				<h1 class="page-header">Control Panel</h1>

				<div class="row placeholders">
					<div class="col-xs-6 col-sm-3 placeholder show_data" data-view="available" data-title="Available">
						<h2><?= \LIS\Utility::formatNumber(\LIS\RentalItem\RentalItem::getAvailableItemCount($pdo)) ?></h2>
						<h4>Available Items</h4>
						<span class="text-muted">Click to View</span>
					</div>
					<div class="col-xs-6 col-sm-3 placeholder show_data" data-view="checked_out" data-title="Checked Out">
						<h2><?= \LIS\Utility::formatNumber(\LIS\Checkout::getItemCheckedOutCount($pdo)) ?></h2>
						<h4>Checked Out Items</h4>
						<span class="text-muted">Click to View</span>
					</div>
					<div class="col-xs-6 col-sm-3 placeholder show_data" data-view="damaged" data-title="Damaged">
						<h2><?= \LIS\Utility::formatNumber(\LIS\RentalItem\RentalItem::getDamagedItemCount($pdo)) ?></h2>
						<h4>Damaged Items</h4>
						<span class="text-muted">Click to View</span>
					</div>
					<div class="col-xs-6 col-sm-3 placeholder show_data" data-view="lost" data-title="Lost">
						<h2><?= \LIS\Utility::formatNumber(\LIS\RentalItem\RentalItem::getLostItemCount($pdo)) ?></h2>
						<h4>Lost Items</h4>
						<span class="text-muted">Click to View</span>
					</div>
				</div>

				<h2 class="sub-header">Available Items</h2>
				<div class="table-responsive">
					<table class="table table-striped" id="view_table">
						<thead>
							<tr>
								<th>ID#</th>
								<th>Title</th>
								<th>Category</th>
								<th>Creators</th>
							</tr>
						</thead>
						<tbody id="available">
							<?php foreach (\LIS\RentalItem\RentalItem::getAllAvailable($pdo) as $item) { ?>
								<tr>
									<td><?= \LIS\Utility::formatNumber($item->getId()) ?></td>
									<td><a href="/item/<?= $item->getId() ?>/"><?= $item->getTitle() ?></td>
									<td><?= $item->getCategory() ?></td>
									<td>
										<?php if ($item->isBook()) { ?>
											<?= implode(", ", \LIS\RentalItem\Author::findAllForBook($pdo, $item)) ?>
										<?php }
										else if ($item->isDVD()) { ?>
											<?= $item->getDirector() ?>
										<?php }
										else if ($item->isMagazine()) { ?>
											<?= $item->getPublication() ?> (#<?= $item->getIssueNumber() ?>)
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tbody id="checked_out" style="display: none;">
							<?php foreach (\LIS\RentalItem\RentalItem::getAllCheckedOut($pdo) as $item) { ?>
								<tr>
									<td><?= \LIS\Utility::formatNumber($item->getId()) ?></td>
									<td><a href="/item/<?= $item->getId() ?>/"><?= $item->getTitle() ?></td>
									<td><?= $item->getCategory() ?></td>
									<td>
										<?php if ($item->isBook()) { ?>
											<?= implode(", ", \LIS\RentalItem\Author::findAllForBook($pdo, $item)) ?>
										<?php }
										else if ($item->isDVD()) { ?>
											<?= $item->getDirector() ?>
										<?php }
										else if ($item->isMagazine()) { ?>
											<?= $item->getPublication() ?> (#<?= $item->getIssueNumber() ?>)
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tbody id="damaged" style="display: none;">
							<?php foreach (\LIS\RentalItem\RentalItem::getAllDamaged($pdo) as $item) { ?>
								<tr>
									<td><?= \LIS\Utility::formatNumber($item->getId()) ?></td>
									<td><a href="/item/<?= $item->getId() ?>/"><?= $item->getTitle() ?></a></td>
									<td><?= $item->getCategory() ?></td>
									<td>
										<?php if ($item->isBook()) { ?>
											<?= implode(", ", \LIS\RentalItem\Author::findAllForBook($pdo, $item)) ?>
										<?php }
										else if ($item->isDVD()) { ?>
											<?= $item->getDirector() ?>
										<?php }
										else if ($item->isMagazine()) { ?>
											<?= $item->getPublication() ?> (#<?= $item->getIssueNumber() ?>)
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tbody id="lost" style="display: none;">
							<?php foreach (\LIS\RentalItem\RentalItem::getAllLost($pdo) as $item) { ?>
								<tr>
									<td><?= \LIS\Utility::formatNumber($item->getId()) ?></td>
									<td><a href="/item/<?= $item->getId() ?>/"><?= $item->getTitle() ?></td>
									<td><?= $item->getCategory() ?></td>
									<td>
										<?php if ($item->isBook()) { ?>
											<?= implode(", ", \LIS\RentalItem\Author::findAllForBook($pdo, $item)) ?>
										<?php }
										else if ($item->isDVD()) { ?>
											<?= $item->getDirector() ?>
										<?php }
										else if ($item->isMagazine()) { ?>
											<?= $item->getPublication() ?> (#<?= $item->getIssueNumber() ?>)
										<?php } ?>
									</td>
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
	<script type="text/javascript">
		$(".show_data").click(function () {
			$("#view_table").find("tbody").hide();
			$("#" + $(this).attr("data-view")).show();
			$(".sub-header").text($(this).attr("data-title") + " Items");
		});
	</script>
</footer>
</body>
</html>