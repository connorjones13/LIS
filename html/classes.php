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
	$rental_item = new \LIS\RentalItem\Magazine($pdo);
	$rental_item->create("Magazine summary is this?", "My First Magazine", 5,
			DateTime::createFromFormat("m-d-Y", "11-7-2015"), 2, "Maxim", 3);
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

	<div class="well-lg">
		<h4>ID: <?= $rental_item->getId(); ?> | Title: <?= $rental_item->getTitle(); ?>
			| Category: <?= $rental_item->getCategory(); ?></h4>
		<p>Summary: <?= $rental_item->getSummary(); ?></p>
		<p>Date Published: <?= $rental_item->getDatePublished()->format("m-d-Y"); ?></p>
		<p>Date Added: <?= $rental_item->getDateAdded()->format("m-d-Y"); ?></p>
		<p>Status: <?= $rental_item->getStatus(); ?></p>
	</div>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>