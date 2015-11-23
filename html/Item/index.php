<?php
	require_once(__DIR__ . "/../../includes/LIS/autoload.php");
	$pdo = new \LIS\Database\PDO_MySQL();
	$controller = new \LIS\Controllers\CreateUserController($pdo);
	$item = new LIS\RentalItem\Book($pdo);

	// this for testing. todo: remove when page is passed an item
	$item->create("This is a book summary.", "My First Book", "Horror",
		DateTime::createFromFormat("m-d-Y", "11-7-2015"), 2, "1230219241", "3214123424", ["Jim", "Bob", "Billy"]);
	$authors = array("John", "Jacob", "Jacky");
	// todo: remove above section

	$page_title = $item->getTitle();
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
	<div class="container ip-container">
		<div class="pull-left ip-img">
			<img src="http://lorempixel.com/400/400/">
		</div>
		<div class="">
			<h1><?= $item->getTitle() ?></h1>
			<h4>Category: <?= $item->getCategory() ?></h4>
			<p><?= $item->getSummary() ?></p>
			<p>ISBN10: <?= $item->getISBN10() ?></p>
			<p>ISBN13: <?= $item->getISBN13() ?></p>
			<p>Published: <?= $item->getDatePublished()->format("m-d-Y") ?></p>
			<!-- if book -->
			<p>Authors: <?= implode(", ", \LIS\RentalItem\Author::findAllForBook($pdo, $item)); ?></p>
			<?php
				if($controller->getSessionUser()) {
					echo '<a href="#" class="btn btn-default btn-success">Reserve</a> ';
				}
				if($controller->getSessionUser()->isAdmin() || $controller->getSessionUser()->isEmployee()) {
					echo '<a href="#" class ="btn btn-default btn-warning">Edit Item</a>';
				}
			?>
		</div>
	</div>
</div>
<footer class="footer">
	<?php require_once(__DIR__ . "/../../includes/html_templates/footer.php"); ?>
</footer>
</body>
</html>
