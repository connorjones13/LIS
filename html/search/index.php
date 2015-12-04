<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\SearchController($pdo);

	if (\LIS\Utility::requestHasGet()) {
		$rental_items = $controller->newSearch($_GET["s"]);
	}

	$page_title = "Search";
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
	<div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 main">
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
					<?php if (isset($rental_items)) { ?>
						<?php foreach ($rental_items as $item) { ?>
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
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>