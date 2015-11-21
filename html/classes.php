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



				<!-- testing magazine -->
				<?php
					$rental_magazine = new \LIS\RentalItem\Magazine($pdo);
					$rental_magazine->create("This is a magazine summary.", "My First Magazine", "Adult",
							DateTime::createFromFormat("m-d-Y", "11-7-2015"), 2, "Maxim", 3);
				?>
				<div class="well-lg">
					<h4>ID: <?= $rental_magazine->getId(); ?> | Title: <?= $rental_magazine->getTitle(); ?>
						| Category: <?= $rental_magazine->getCategory(); ?></h4>
					<p>Summary: <?= $rental_magazine->getSummary(); ?></p>
					<p>Date Published: <?= $rental_magazine->getDatePublished()->format("m-d-Y"); ?></p>
					<p>Date Added: <?= $rental_magazine->getDateAdded()->format("m-d-Y"); ?></p>
					<p>Status: <?= $rental_magazine->getStatus(); ?></p>
					<p>Publisher: <?= $rental_magazine->getPublication(); ?></p>
				</div>

				<!-- testing book -->
				<?php
					$rental_book = new \LIS\RentalItem\Book($pdo);
					$rental_book->create("This is a book summary.", "My First Book", "Horror",
							DateTime::createFromFormat("m-d-Y", "11-7-2015"), 2, "", "", ["Jim", "Bob", "Billy"]);
					$authors = array("John", "Jacob", "Jacky");
					\LIS\RentalItem\Author::deleteAllForBook($pdo, $rental_book);
					$authors = \LIS\RentalItem\Author::createNewForBook($pdo, $rental_book, $authors);
				?>
				<div class="well-lg">
					<h4>ID: <?= $rental_book->getId(); ?> | Title: <?= $rental_book->getTitle(); ?>
						| Category: <?= $rental_book->getCategory(); ?></h4>
					<p>Summary: <?= $rental_book->getSummary(); ?></p>
					<p>Date Published: <?= $rental_book->getDatePublished()->format("m-d-Y"); ?></p>
					<p>Date Added: <?= $rental_book->getDateAdded()->format("m-d-Y"); ?></p>
					<p>Status: <?= $rental_book->getStatus(); ?></p>
					<p>Authors: <?= implode(", ", \LIS\RentalItem\Author::findAllForBook($pdo, $rental_book)); ?></p>

				</div>

				<!-- testing dvd -->
				<?php
					$rental_dvd = new \LIS\RentalItem\DVD($pdo);
					$rental_dvd->create("This is a DVD summary.", "My First DVD", "Action",
							DateTime::createFromFormat("m-d-Y", "05-15-2011"), 3, "Joseph Maxwell");
				?>
				<div class="well-lg">
					<h4>ID: <?= $rental_dvd->getId(); ?> | Title: <?= $rental_dvd->getTitle(); ?>
						| Category: <?= $rental_dvd->getCategory(); ?></h4>
					<p>Summary: <?= $rental_dvd->getSummary(); ?></p>
					<p>Date Published: <?= $rental_dvd->getDatePublished()->format("m-d-Y"); ?></p>
					<p>Date Added: <?= $rental_dvd->getDateAdded()->format("m-d-Y"); ?></p>
					<p>Status: <?= $rental_dvd->getStatus(); ?></p>
					<p>Director: <?= $rental_dvd->getDirector() ?></p>
				</div>
			</div>
		</div>
		<footer class="footer">
			<?php require_once(__DIR__ . "/../includes/html_templates/footer.php"); ?>
		</footer>
	</body>
</html>