<?php
require_once(__DIR__ . "/../../includes/LIS/autoload.php");
$pdo = new \LIS\Database\PDO_MySQL();
$controller = new \LIS\Controllers\RentalItemController($pdo);

$page_title = "Browse Rental Items";
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
			<div class="text-center"><h1>Browse Available Rental Items</h1></div>
			<div class="col-lg-8 col-md-8 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-1">
				<h2>Books</h2>
				<?php $books = \LIS\RentalItem\Book::getAllAvailable($pdo); ?>
				<?php foreach ($books as $book) { ?>
					<div class="col-sm-6 col-md-3 list_items">
						<h4><b><a href="/item/<?= $book->getId() ?>/"><?= $book->getTitle(); ?></a></b></h4>

						<p>Category: <?= $book->getCategory(); ?></p>

						<p>Summary: <?= $book->getSummary(); ?></p>

						<p>Date Published: <?= $book->getDatePublished()->format("m-d-Y"); ?></p>

						<p>Authors: <?= implode(", ", \LIS\RentalItem\Author::findAllForBook($pdo, $book)); ?></p>
					</div>
				<?php } ?>
			</div>
			<div class="center col-lg-8 col-md-8 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-1">
				<h2>Magazines</h2>
				<?php $magazines = \LIS\RentalItem\Magazine::getAllAvailable($pdo); ?>
				<?php foreach ($magazines as $magazine) { ?>
					<div class="col-sm-6 col-md-3 list_items">
						<h4><b><a href="/item/<?= $magazine->getId() ?>/"><?= $magazine->getTitle(); ?></a></b></h4>

						<p>Category: <?= $magazine->getCategory(); ?></p>

						<p>Summary: <?= $magazine->getSummary(); ?></p>

						<p>Date Published: <?= $magazine->getDatePublished()->format("m-d-Y"); ?></p>

						<p>Publication: <?= $magazine->getPublication(); ?></p>
					</div>
				<?php } ?>
			</div>
			<div class="center col-lg-8 col-md-8 col-sm-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-1">
				<h2>DVDs</h2>
				<?php $dvds = \LIS\RentalItem\DVD::getAllAvailable($pdo); ?>
				<?php foreach ($dvds as $dvd) { ?>
					<div class="col-sm-6 col-md-3 list_items">
						<h4><b><a href="/item/<?= $dvd->getId() ?>/"><?= $dvd->getTitle(); ?></a></b></h4>

						<p>Category: <?= $dvd->getCategory(); ?></p>

						<p>Summary: <?= $dvd->getSummary(); ?></p>

						<p>Date Published: <?= $dvd->getDatePublished()->format("m-d-Y"); ?></p>

						<p>Director: <?= $dvd->getDirector(); ?></p>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>
