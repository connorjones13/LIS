<?php
	require_once(__DIR__ . "/../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\BaseController($pdo);

	$page_title = "Test Classes";
?>
<!doctype html>
<html lang="en">
	<head>
		<?php require_once(__DIR__ . "/../includes/html_templates/head.php"); ?>
	</head>
	<body>
		<header class="navbar navbar-fixed-top navbar-inverse">
			<?php require_once(__DIR__ . "/../includes/html_templates/header.php"); ?>
		</header>
		<div class="content">
			<div class="container">
				<?php if ($controller->getSessionUser()) { ?>
					ID:<br>
					<?= $controller->getSessionUser()->getId(); ?><br><br>
					Full Name:<br>
					<?= $controller->getSessionUser()->getNameFull(); ?><br><br>
					Email:<br>
					<?= $controller->getSessionUser()->getEmail(); ?><br><br>
					Address:<br>
					<?= $controller->getSessionUser()->getAddressFull(); ?><br><br>
					DOB:<br>
					<?= $controller->getSessionUser()->getDateOfBirth()->format("m-d-Y"); ?><br><br>
					DSU:<br>
					<?= $controller->getSessionUser()->getDateSignedUp()->format("m-d-Y"); ?><br><br>
					Library Card:<br>
					<?= $controller->getSessionUser()->getLibraryCard()->getNumber(); ?><br><br>
					Card Issue Date:<br>
					<?= $controller->getSessionUser()->getLibraryCard()->getDateIssued()->format("m-d-Y"); ?>
				<?php } else { ?>
					<p class="alert bg-warning">
						You are not logged in. Please login to see some user output.
					</p>
				<?php } ?>

				<?php foreach (\LIS\RentalItem\RentalItem::getAllLost($pdo) as $ri) { ?>
					<div class="well-lg">
						<h4>ID: <?= $ri->getId(); ?> | Title: <?= $ri->getTitle(); ?>
							| Category: <?= $ri->getCategory(); ?></h4>
						<p>Summary: <?= $ri->getSummary(); ?></p>
						<p>Date Published: <?= $ri->getDatePublished()->format("m-d-Y"); ?></p>
						<p>Date Added: <?= $ri->getDateAdded()->format("m-d-Y"); ?></p>
						<p>Status: <?= $ri->getStatus(); ?></p>
						<?php if ($ri->isBook()) { ?>
							<p>Authors: <?= implode(", ", \LIS\RentalItem\Author::findAllForBook($pdo, $ri)); ?></p>
						<?php } else if ($ri->isDVD()) { ?>
							<p>Director: <?= $ri->getDirector(); ?></p>
						<?php } else { ?>
							<p>Publisher: <?= $ri->getPublication(); ?></p>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<footer class="footer">
			<?php require_once(__DIR__ . "/../includes/html_templates/footer.php"); ?>
		</footer>
	</body>
</html>