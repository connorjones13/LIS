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
	<?php
	$user = \LIS\User\User::find($pdo, 1);
	$user->updatePassword("password");

//	$rental_item = \LIS\RentalItem\RentalItem::find($pdo, 1);

	//Example code to create a new Admin
	if (false) {
		$user = new \LIS\User\Admin($pdo);
		$user->create("Testy", "McTesterson", "test@example.com", "1235554321", 1,
			DateTime::createFromFormat("m-d-Y", "11-10-1993"), "2 Test Rd", "", "12345", "Testin",
			"TE", "USA", "test_hash");
	}
	?>
	ID:<br>
	<?= $user->getId(); ?><br><br>
	Full Name:<br>
	<?= $user->getNameFull(); ?><br><br>
	Email:<br>
	<?= $user->getEmail(); ?><br><br>
	Address:<br>
	<?= $user->getAddressFull(); ?><br><br>
	DOB:<br>
	<?= $user->getDateOfBirth()->format("m-d-Y"); ?><br><br>
	DSU:<br>
	<?= $user->getDateSignedUp()->format("m-d-Y"); ?><br><br>
	Library Card:<br>
	<?= $user->getLibraryCardNumber(); ?><br><br>
	Card Issue Date:<br>
	<?= $user->getLibraryCardDateIssued()->format("m-d-Y"); ?>


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
	?>
	<div class="well-lg">
		<h4>ID: <?= $rental_book->getId(); ?> | Title: <?= $rental_book->getTitle(); ?>
			| Category: <?= $rental_book->getCategory(); ?></h4>
		<p>Summary: <?= $rental_book->getSummary(); ?></p>
		<p>Date Published: <?= $rental_book->getDatePublished()->format("m-d-Y"); ?></p>
		<p>Date Added: <?= $rental_book->getDateAdded()->format("m-d-Y"); ?></p>
		<p>Status: <?= $rental_book->getStatus(); ?></p>
		<p>Authors: <?= implode(", ", $rental_book->getAuthors()); ?></p>
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
<footer class="footer">
	<?php require_once(__DIR__ . "/../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>